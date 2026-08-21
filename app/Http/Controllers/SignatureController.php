<?php

namespace App\Http\Controllers;

use App\Models\Signature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use App\Services\PlanLimitService;

class SignatureController extends Controller
{
    public function index(Request $request)
    {
        $this->ensurePermission($request, 'signature.view');

        $canManageAll = $this->canManageAll($request);

        return view('signatures.index', [
            'signatures' => $this->getItems($request),
            'users' => $canManageAll
                ? User::active()->orderBy('name')->get()
                : collect([$request->user()]),
            'canManageAll' => $canManageAll,
        ]);
    }

    public function cards(Request $request)
    {
        $this->ensurePermission($request, 'signature.view');

        return view('signatures.partials.cards', [
            'signatures' => $this->getItems($request),
        ]);
    }

    public function store(Request $request, PlanLimitService $planLimits)
    {
        $this->ensurePermission($request, 'signature.create');
        $planLimits->ensureAvailable('signatures');

        $this->setOwner($request);

        $data = $this->validateData($request);
        $certificateData = $this->readCertificateData($request);

        $this->clearDefaultSignature($request);

        Signature::create([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'pfx_path' => $this->storePfx($request),
            'pfx_disk' => 'local',
            'signature_image' => $this->storeSignatureImage($request),
            'signature_image_disk' => 'local',
            'pfx_password' => $this->rememberedPassword($request),
            'certificate_data' => $certificateData,
            'is_default' => $request->boolean('is_default'),
            'active' => $request->boolean('active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Firma creada correctamente.',
        ]);
    }

    public function update(Request $request, Signature $signature)
    {
        $this->ensurePermission($request, 'signature.edit');
        $this->ensureOwnership($request, $signature);

        $this->setOwner($request);

        $data = $this->validateData($request, $signature);
        $certificateData = $signature->certificate_data;

        if ($request->hasFile('pfx_file')) {
            $certificateData = $this->readCertificateData($request);

            $this->deleteFile($signature->pfx_path, $signature->pfx_disk);
            $signature->pfx_path = $this->storePfx($request);
            $signature->pfx_disk = 'local';
        }

        if ($request->hasFile('signature_image')) {
            $this->deleteFile($signature->signature_image, $signature->signature_image_disk);
            $signature->signature_image = $this->storeSignatureImage($request);
            $signature->signature_image_disk = 'local';
        }

        $this->clearDefaultSignature($request, $signature);

        $signature->update([
            'user_id' => $data['user_id'],
            'type' => $data['type'],
            'pfx_password' => $this->rememberedPassword($request, $signature),
            'certificate_data' => $certificateData,
            'is_default' => $request->boolean('is_default'),
            'active' => $request->boolean('active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Firma actualizada correctamente.',
        ]);
    }

    public function destroy(Request $request, Signature $signature)
    {
        $this->ensurePermission($request, 'signature.delete');
        $this->ensureOwnership($request, $signature);

        if (! $signature->canDelete()) {
            return response()->json([
                'message' => 'No se puede eliminar una firma que ya fue utilizada.',
            ], 422);
        }

        $this->deleteFile($signature->pfx_path, $signature->pfx_disk);
        $this->deleteFile($signature->signature_image, $signature->signature_image_disk);

        $signature->delete();

        return response()->json([
            'success' => true,
            'message' => 'Firma eliminada correctamente.',
        ]);
    }

    public function active(Request $request, Signature $signature)
    {
        $this->ensurePermission($request, 'signature.edit');
        $this->ensureOwnership($request, $signature);

        $signature->update([
            'active' => ! $signature->active,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Estado de la firma actualizado correctamente.',
        ]);
    }

    public function image(Request $request, Signature $signature)
    {
        $this->ensureOwnership($request, $signature);

        abort_unless($signature->signature_image, 404);

        $disk = $signature->signature_image_disk ?: 'local';
        abort_unless(Storage::disk($disk)->exists($signature->signature_image), 404);

        return response()->file(Storage::disk($disk)->path($signature->signature_image));
    }

    private function getItems(Request $request)
    {
        $search = trim((string) $request->input('search'));
        $active = $request->input('active', '1');
        $type = $request->input('type');
        $userId = $request->input('user_id');

        if (! in_array($active, ['0', '1', 'all'], true)) {
            $active = '1';
        }

        return Signature::query()
            ->with('user')
            ->when(
                ! $this->canManageAll($request),
                fn($query) => $query->where('user_id', $request->user()->id)
            )
            ->when(
                $this->canManageAll($request) && filled($userId),
                fn($query) => $query->where('user_id', $userId)
            )
            ->when(
                in_array($type, ['official', 'visual'], true),
                fn($query) => $query->where('type', $type)
            )
            ->when(
                $active !== 'all',
                fn($query) => $query->where('active', $active === '1')
            )
            ->when($search !== '', function ($query) use ($search) {
                $normalizedSearch = Str::lower($search);
                $signatureId = $this->signatureIdFromCode($search);
                $types = $this->typesFromSearch($normalizedSearch);

                $query->where(function ($q) use (
                    $search,
                    $signatureId,
                    $types
                ) {
                    $q->whereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });

                    if ($signatureId) {
                        $q->orWhere('id', $signatureId);
                    }

                    if ($types) {
                        $q->orWhereIn('type', $types);
                    }

                    $q->orWhereRaw(
                        "JSON_SEARCH(
                            certificate_data,
                            'one',
                            ?,
                            NULL,
                            '$.subject.CN',
                            '$.subject.emailAddress',
                            '$.subject.serialNumber',
                            '$.issuer.O',
                            '$.issuer.CN',
                            '$.serialNumberHex'
                        ) IS NOT NULL",
                        ["%{$search}%"]
                    );
                });
            })
            ->latest('id')
            ->paginate(config('crud.pagination', 12))
            ->withQueryString();
    }

    private function signatureIdFromCode(string $search): ?int
    {
        if (! preg_match('/^(?:FIR-?)?(\d+)$/i', trim($search), $matches)) {
            return null;
        }

        return (int) $matches[1] ?: null;
    }

    private function typesFromSearch(string $search): array
    {
        if (Str::contains($search, ['certificado', 'oficial', 'pfx'])) {
            return ['official'];
        }

        if (Str::contains($search, 'visual')) {
            return ['visual'];
        }

        return [];
    }

    private function validateData(
        Request $request,
        ?Signature $signature = null
    ): array {
        $request->merge([
            'remember_certificate_password' => $request->boolean('remember_certificate_password'),
            'active' => $request->boolean('active'),
        ]);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'type' => ['required', Rule::in(['official', 'visual'])],
            'pfx_file' => ['nullable', 'file', 'max:5120'],
            'signature_image' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
            'pfx_password' => ['nullable', 'string'],
            'remember_certificate_password' => ['nullable', 'boolean'],
            'active' => ['nullable', 'boolean'],
        ]);

        if (
            $data['type'] === 'official'
            && ! $request->hasFile('pfx_file')
            && ! $signature?->pfx_path
        ) {
            throw ValidationException::withMessages([
                'pfx_file' => 'El certificado PFX es obligatorio.',
            ]);
        }

        if (
            $data['type'] === 'visual'
            && ! $request->hasFile('signature_image')
            && ! $signature?->signature_image
        ) {
            throw ValidationException::withMessages([
                'signature_image' => 'La imagen de firma es obligatoria.',
            ]);
        }

        return $data;
    }

    private function readCertificateData(Request $request): ?array
    {
        if (! $request->hasFile('pfx_file')) {
            return null;
        }

        if (! $request->filled('pfx_password')) {
            throw ValidationException::withMessages([
                'pfx_password' => 'Debe ingresar la contraseña del certificado.',
            ]);
        }

        $file = $request->file('pfx_file');

        if (strtolower($file->getClientOriginalExtension()) !== 'pfx') {
            throw ValidationException::withMessages([
                'pfx_file' => 'Debe subir un certificado PFX válido.',
            ]);
        }

        $certificates = [];

        $read = openssl_pkcs12_read(
            file_get_contents($file->getRealPath()),
            $certificates,
            $request->pfx_password
        );

        if (! $read) {
            throw ValidationException::withMessages([
                'pfx_password' => 'No se pudo abrir el certificado con esa contraseña.',
            ]);
        }

        return json_decode(
            json_encode(openssl_x509_parse($certificates['cert'])),
            true
        );
    }

    private function rememberedPassword(Request $request, ?Signature $signature = null): ?string
    {
        if (! $request->boolean('remember_certificate_password')) {
            return null;
        }

        if ($request->filled('pfx_password')) {
            return encrypt($request->string('pfx_password')->toString());
        }

        return $signature?->pfx_password;
    }

    private function storePfx(Request $request): ?string
    {
        return $request->hasFile('pfx_file')
            ? $request->file('pfx_file')->store('signatures/pfx', 'local')
            : null;
    }

    private function storeSignatureImage(Request $request): ?string
    {
        return $request->hasFile('signature_image')
            ? $request->file('signature_image')->store('signatures/images', 'local')
            : null;
    }

    private function deleteFile(?string $path, ?string $disk = 'local'): void
    {
        if ($path) {
            Storage::disk($disk ?: 'local')->delete($path);
        }
    }

    private function clearDefaultSignature(
        Request $request,
        ?Signature $signature = null
    ): void {
        if (! $request->boolean('is_default')) {
            return;
        }

        Signature::where('user_id', $request->user_id)
            ->when($signature, fn($query) => $query->where('id', '!=', $signature->id))
            ->update(['is_default' => false]);
    }

    private function setOwner(Request $request): void
    {
        if (! $this->canManageAll($request)) {
            $request->merge(['user_id' => $request->user()->id]);
        }
    }

    private function canManageAll(Request $request): bool
    {
        return $request->user()->isSystemOwner()
            || $request->user()->can('signature.manage-all');
    }

    private function ensurePermission(Request $request, string $permission): void
    {
        abort_unless($request->user()->can($permission), 403);
    }

    private function ensureOwnership(
        Request $request,
        Signature $signature
    ): void {
        abort_unless(
            $this->canManageAll($request)
                || $signature->user_id === $request->user()->id,
            403
        );
    }
}
