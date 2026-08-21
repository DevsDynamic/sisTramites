# Servicio interno de firma PAdES

Este componente pertenece a sisTramites. No se publica en Internet: Laravel lo llama por `127.0.0.1:8088` dentro del mismo VPS.

## Preparación del VPS

1. Genera un secreto largo y único para `INTERNAL_SIGNER_TOKEN`.
2. En el `.env` de Laravel configura:

```dotenv
INTERNAL_SIGNER_URL=http://127.0.0.1:8088
INTERNAL_SIGNER_TOKEN=secreto-largo-y-unico
INTERNAL_SIGNER_TIMEOUT=120
```

3. Inicia el componente desde la raíz del proyecto:

```bash
docker compose -f docker-compose.signing.yml up -d --build
```

El puerto está ligado a `127.0.0.1`, por lo que no debe abrirse en firewall ni en el proxy público.

## Alcance inicial

- Firma CMS/PAdES incremental con certificados PFX.
- Conserva las revisiones y firmas oficiales previas del PDF.
- Sello visible de firma o visto bueno.
- Primera, última, todas o una página específica.
- Coordenadas manuales proporcionales a la hoja.

El siguiente endurecimiento para producción es agregar TSA/LTA, validación OCSP/CRL y un almacén de claves/HSM. La contraseña del PFX no se debe registrar en logs.
