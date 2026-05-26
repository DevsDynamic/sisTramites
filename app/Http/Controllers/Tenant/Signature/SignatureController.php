<?php

namespace App\Http\Controllers\Tenant\Signature;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Signature;
use App\Models\Tenant\TenantUser;
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

        $users = TenantUser::on('tenant')->active()->get();

        return view(
            'tenant.signatures.index',
            compact(
                'signatures',
                'users'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'user_id' => [
                'required',
                'exists:tenant.users,id'
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

            'active' => [
                'nullable',
                'boolean'
            ],
        ]);

        /*
    |--------------------------------------------------------------------------
    | VALIDACIONES POR TIPO
    |--------------------------------------------------------------------------
    */

        /*
    | OFFICIAL
    */

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

            /*
        |--------------------------------------------------------------------------
        | VALIDAR CERTIFICADO REAL
        |--------------------------------------------------------------------------
        */

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
        }

        /*
    | VISUAL
    */

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

        /*
    |--------------------------------------------------------------------------
    | UPLOADS
    |--------------------------------------------------------------------------
    */

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

        /*
    |--------------------------------------------------------------------------
    | DEFAULT ÚNICA
    |--------------------------------------------------------------------------
    */

        if ($request->boolean('is_default')) {

            Signature::where(
                'user_id',
                $request->user_id
            )->update([
                'is_default' => false
            ]);
        }

        /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

        Signature::create([

            'user_id' => $request->user_id,

            'type' => $request->type,

            'pfx_path' => $pfxPath,

            'signature_image' => $imagePath,

            'pfx_password' => $request->pfx_password
                ? encrypt($request->pfx_password)
                : null,

            'expires_at' => $request->expires_at,

            'is_default' => $request->boolean('is_default'),

            'active' => $request->boolean('active'),
        ]);

        return back()->with(
            'success',
            'Firma creada correctamente.'
        );

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Firma registrada correctamente.'
        // ]);
    }

    public function update(
        Request $request,
        Signature $signature
    ) {

        $validated = $request->validate([

            'user_id' => [
                'required',
                'exists:tenant.users,id'
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

            if ($signature->pfx_path) {

                Storage::disk('public')
                    ->delete($signature->pfx_path);
            }

            $signature->pfx_path = $request
                ->file('pfx_file')
                ->store(
                    'signatures/pfx',
                    'public'
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

            'expires_at' => $request->expires_at,

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

        /* DELETE */
        $signature->delete();

        return response()->json([
            'success' => true,
            'message' => 'Firma eliminada correctamente.'
        ]);
    }
}
