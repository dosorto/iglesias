<div>

    <div class="flex justify-end mb-3">

    <a href="{{ route('instructor.inscripcion.create', $instructor) }}"
    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
    Nueva Inscripción
    </a>

    </div>

<table class="w-full text-sm">
<table class="w-full text-sm">
    <thead>
        <tr class="border-b">
            <th class="text-left p-2">Persona</th>
            <th class="text-left p-2">Curso</th>
            <th class="text-left p-2">Fecha Inscripción</th>
            <th class="text-left p-2">Aprobado</th>
            <th class="text-left p-2">Certificado</th>
            <th class="text-left p-2">Fecha Certificado</th>
        </tr>
    </thead>

    <tbody>
        @forelse($inscripciones as $inscripcion)

        <tr class="border-b">

            <td class="p-2">
                {{ $inscripcion->feligres->persona->nombre_completo }}
            </td>

            <td class="p-2">
                {{ $inscripcion->curso->nombre }}
            </td>

            <td class="p-2">
                {{ $inscripcion->fecha_inscripcion }}
            </td>

            <td class="p-2">
                {{ $inscripcion->aprobado ? 'Sí' : 'No' }}
            </td>

            <td class="p-2">
                {{ $inscripcion->certificado_emitido ? 'Emitido' : 'No' }}
            </td>

            <td class="p-2">
                {{ $inscripcion->fecha_certificado ?? '-' }}
            </td>

        </tr>

        @empty

        <tr>
            <td colspan="6" class="text-center p-4 text-gray-500">
                No hay inscripciones registradas
            </td>
        </tr>

        @endforelse
    </tbody>

</table>

<div class="mt-4">
    {{ $inscripciones->links() }}
</div>

</div>