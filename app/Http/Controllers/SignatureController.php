<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Signature;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class SignatureController extends Controller
{
    public function index()
    {
        $signatures = Signature::with('user')
            ->latest()
            ->paginate(12);
        $users = User::active()
            ->orderBy('name')
            ->get();

        return view(
            'signatures.index',
            compact(
                'signatures',
                'users'
            )
        );
    }

    public function cards()
    {
        $signatures = Signature::with('user')
            ->latest()
            ->paginate(12);

        return view(
            'signatures.partials.cards',
            compact('signatures')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'user_id' => [
                'required',
                'exists:users,id'
            ],

            'type' => [
                'required',
                Rule::in([
                    'official',
                    'visual'
                ])
            ],

            'pfx_file' => [
                'nullable',
                'file',
                'max:5120',
            ],

            'signature_image' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg',
                'max:2048'
            ],

            'pfx_password' => [
                'nullable',
                'string'
            ],

            'expires_at' => [
                'nullable',
                'date'
            ],
        ]);

        $validated['active'] = $request->boolean('active');

        /* VALIDACIONES POR TIPO */
        $certificateData = null;
        /* OFFICIAL */
        if ($request->type === 'official') {
            if (!$request->hasFile('pfx_file')) {
                return response()->json([
                    'message' => 'Debe subir el certificado PFX.',
                    'errors' => [
                        'pfx_file' => [
                            'El certificado es obligatorio.'
                        ]
                    ]
                ], 422);
            }

            if (!$request->pfx_password) {
                return response()->json([
                    'message' => 'Debe ingresar la contraseña.',
                    'errors' => [
                        'pfx_password' => [
                            'La contraseña es obligatoria.'
                        ]
                    ]
                ], 422);
            }

            $extension = strtolower(
                $request->file('pfx_file')
                    ->getClientOriginalExtension()
            );

            if ($extension !== 'pfx') {

                return response()->json([
                    'message' => 'Solo archivos .pfx',
                    'errors' => [
                        'pfx_file' => [
                            'Debe subir un certificado válido.'
                        ]
                    ]
                ], 422);
            }

            /* VALIDAR CERTIFICADO REAL */
            $tempPath = $request
                ->file('pfx_file')
                ->getRealPath();

            $password = $request->pfx_password;

            $certificates = [];

            $read = openssl_pkcs12_read(
                file_get_contents($tempPath),
                $certificates,
                $password
            );

            if (!$read) {

                return response()->json([
                    'message' => 'Contraseña o certificado inválido.',
                    'errors' => [
                        'pfx_password' => [
                            'No se pudo abrir el certificado.'
                        ]
                    ]
                ], 422);
            }

            

            if ($read) {

                $certInfo = openssl_x509_parse(
                    $certificates['cert']
                );

                $certificateData = json_decode(
                    json_encode($certInfo),
                    true
                );
            }
        }

        /* VISUAL */
        if ($request->type === 'visual') {

            if (!$request->hasFile('signature_image')) {

                return response()->json([
                    'message' => 'Debe subir una firma visual.',
                    'errors' => [
                        'signature_image' => [
                            'La imagen es obligatoria.'
                        ]
                    ]
                ], 422);
            }
        }

        /* UPLOADS */
        $pfxPath = null;

        if ($request->hasFile('pfx_file')) {

            $pfxPath = $request
                ->file('pfx_file')
                ->store(
                    'signatures/pfx',
                    'public'
                );
        }

        $imagePath = null;

        if ($request->hasFile('signature_image')) {

            $imagePath = $request
                ->file('signature_image')
                ->store(
                    'signatures/images',
                    'public'
                );
        }

        /* DEFAULT ÚNICA */
        if ($request->boolean('is_default')) {

            Signature::where(
                'user_id',
                $request->user_id
            )->update([
                'is_default' => false
            ]);
        }

        /* CREATE */
        Signature::create([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'pfx_path' => $pfxPath,
            'signature_image' => $imagePath,
            'pfx_password' => $request->pfx_password
                ? encrypt($request->pfx_password)
                : null,
            'certificate_data' => $certificateData,
            'is_default' => $request->boolean('is_default'),
            'active' => $request->boolean('active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Firma creada correctamente.'
        ]);
    }

    public function update(Request $request, $id)
    {
        $signature = Signature::findOrFail($id);

        $validated = $request->validate([

            'user_id' => [
                'required',
                'exists:users,id'
            ],

            'type' => [
                'required',
                Rule::in([
                    'official',
                    'visual'
                ])
            ],

            'pfx_file' => [
                'nullable',
                'file',
                'mimes:pfx'
            ],

            'signature_image' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg',
                'max:2048'
            ],

            'pfx_password' => [
                'nullable',
                'string'
            ],

            'expires_at' => [
                'nullable',
                'date'
            ],

            'active' => [
                'nullable',
                'boolean'
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | REEMPLAZAR PFX
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('pfx_file')) {

            $tempPath =
                $request->file('pfx_file')
                ->getRealPath();

            $password =
                $request->pfx_password;

            $certificates = [];

            $read = openssl_pkcs12_read(
                file_get_contents($tempPath),
                $certificates,
                $password
            );

            if (!$read) {

                return response()->json([
                    'message' => 'Certificado inválido.'
                ], 422);
            }

            $certInfo =
                openssl_x509_parse(
                    $certificates['cert']
                );

            $certificateData = json_decode(
                json_encode($certInfo),
                true
            );
        }

        /*
        |--------------------------------------------------------------------------
        | REEMPLAZAR IMAGEN
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('signature_image')) {

            if ($signature->signature_image) {

                Storage::disk('public')
                    ->delete($signature->signature_image);
            }

            $signature->signature_image = $request
                ->file('signature_image')
                ->store(
                    'signatures/images',
                    'public'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | DEFAULT ÚNICA
        |--------------------------------------------------------------------------
        */

        if ($request->boolean('is_default')) {

            Signature::where(
                'user_id',
                $request->user_id
            )
                ->where('id', '!=', $signature->id)
                ->update([
                    'is_default' => false
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        $signature->update([
            'user_id' => $request->user_id,
            'type' => $request->type,
            'pfx_password' => $request->pfx_password
                ? encrypt($request->pfx_password)
                : $signature->pfx_password,
            'certificate_data' => $certificateData,
            'is_default' => $request->boolean('is_default'),
            'active' => $request->boolean('active'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Firma actualizada correctamente.'
        ]);
    }

    public function destroy(Signature $signature)
    {
        /* DELETE FILES */
        if ($signature->pfx_path) {

            Storage::disk('public')
                ->delete($signature->pfx_path);
        }

        if ($signature->signature_image) {

            Storage::disk('public')
                ->delete($signature->signature_image);
        }

        $signature->update([
            'active' => false
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Firma eliminada correctamente.'
        ]);
    }
}
