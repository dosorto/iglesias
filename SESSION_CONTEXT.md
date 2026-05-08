# Contexto de sesión — iglesias (NO SUBIR AL REPO)

## Estado actual — 2026-05-07

### Rama activa
`iglesia-test` (base: `main`)

### Últimos commits en esta sesión
- `1564345` fix: corregir acentos en mensajes y textos visibles
- `edd99cf` fix: eliminar QR codes de todos los certificados PDF

### Stack
Laravel 12 + PHP 8.2 + Livewire 3 + multi-tenant + Dompdf

### Tenant de prueba
DB: `tenant_espiritu_santo_monjaras_11`, iglesia_id = 1
26 personas de prueba (ids 1–26), Vicente Rueda id=1 (encargado)
Encargado model feligres #25 → persona id=26 (Vicente Rueda)

---

## Tareas completadas en esta sesión

### QR codes — COMPLETO
- Vistas blade: bautismo, matrimonio, primera-comunion, confirmacion, curso — CSS y HTML eliminados
- Controladores: Bautismo, Confirmacion, Matrimonio, PrimeraComunion, InscripcionCurso — imports, generación y payload eliminados
- Layout version bumpeado a `header-config-v6` en todos los controladores

### Acentos — COMPLETO (parcial, los más visibles)
Archivos corregidos:
- Controllers: CompanySettings, Confirmacion, IglesiaController, PrimeraComunion
- Requests: StoreIglesiaRequest, UpdateIglesiaRequest
- Livewire: BautismoCreate, InstructorCreate
- Vistas: bautismo/certificado-pdf, configuracion/empresa, dashboard, documentos/validacion, bautismo-create, curso-index, instructor-dashboard, primera-comunion-show

### Banners de advertencia de duplicado (amber) — COMPLETO
Añadidos a 6 archivos blade (edit/create de bautismo, confirmacion, matrimonio, primera-comunion)

### Credenciales al crear instructor — COMPLETO
InstructorCreate.php y instructor-create.blade.php: panel azul con radio generar/omitir cuando la persona ya tiene correo
CursoCreate.php y curso-create.blade.php: ídem para instructor

### Borde dorado eliminado — COMPLETO
bautismo/certificado-pdf.blade.php y confirmacion/certificado-pdf.blade.php: `border: none`

### Espacio primera comunión — COMPLETO
primera-comunion/certificado-pdf.blade.php: padding, line-height y márgenes ajustados

---

## Tareas PENDIENTES (lista original del usuario)

1. **Logos — tamaño** — ajustar tamaño de logos en certificados (todos)
2. **Nombres en mayúsculas** — forzar mayúsculas en nombres de personas
3. **Nombre de archivo con id+fecha** — PDF descargado con nombre tipo `bautismo-001-20240501.pdf`
4. **Alerta datos faltantes** — si faltan datos clave para el certificado, alertar antes de generar
5. **Quitar líneas de los formatos** — eliminar líneas/subrayados de los certificados PDF
6. **Quitar sello de agua** — eliminar marca de agua (watermark logo) de los certificados
7. **Mejora certificado confirmación** — mejorar diseño/espacio igual que se hizo con primera comunión
8. **Estandarizar todos los certificados** — misma estructura/estilo en todos
9. **Estandarizar fuentes** — tipografía consistente en todos los certificados
10. **Login personalizado con foto de parroquia** — pantalla de login con imagen de la iglesia

---

## Preferencias del usuario
- Commits en español (igual que commits previos del repo)
- Un commit por cada tarea/paso
- Sin comentarios innecesarios en el código
- No agregar features extras fuera del scope pedido

---

## Archivos clave
- Certificados PDF: `resources/views/{bautismo,confirmacion,matrimonio,primera-comunion}/certificado-pdf.blade.php`
- Curso PDF: `resources/views/certificados/curso-pdf.blade.php`
- Controladores PDF: `app/Http/Controllers/{Bautismo,Confirmacion,Matrimonio,PrimeraComunion,InscripcionCurso}Controller.php`
- Livewire: `app/Livewire/` (Create/Edit/Show/Index por módulo)
- Config iglesia: `app/Models/TenantIglesia.php`, campos `orientacion_certificado_*`, `path_logo`, `path_logo_derecha`
- Layout version actual: `'header-config-v6'` (en todos los controladores de PDF)
