<?php

namespace App\Livewire\Instructor;

use App\Models\Curso;
use App\Models\InscripcionCurso;
use App\Models\Instructor;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class InstructorDashboard extends Component
{
    public array $stats = [
        'total_alumnos' => 0,
        'cursos_activos' => 0,
        'tasa_aprobacion' => 0.0,
    ];

    public bool $firmaConfigurada = false;
    public array $cursosEnCurso = [];
    public array $actividadReciente = [];
    public string $nombreInstructor = 'Instructor';
    public string $saludoInstructor = 'Bienvenido';
    public ?int $instructorId = null;
    // Problema #21: Recursos de capacitación para el onboarding
    public array $recursosCapacitacion = [];

    public function mount(): void
    {
        $authUser = Auth::user();

        if (! $authUser) {
            abort(403);
        }

        $currentUser = User::with('roles')->find($authUser->id);
        if (! $currentUser || ! $currentUser->roles?->contains('name', 'instructor')) {
            abort(403, 'No tienes permiso para acceder al panel de instructor.');
        }

        $instructorId = Instructor::resolveIdFromAuthEmail($authUser->email);
        if (! $instructorId) {
            abort(403, 'No se pudo vincular tu usuario con un instructor.');
        }
        $this->instructorId = (int) $instructorId;

        $instructor = Instructor::with('feligres.persona')->findOrFail($instructorId);
        $persona = $instructor->feligres?->persona;
        $this->firmaConfigurada = ! empty($instructor->path_firma);

        if ($persona) {
            $this->nombreInstructor = trim(($persona->primer_nombre ?? '') . ' ' . ($persona->primer_apellido ?? ''));

            $sexo = strtoupper((string) ($persona->sexo ?? ''));
            $this->saludoInstructor = $sexo === 'F' ? 'Bienvenida' : 'Bienvenido';
        }

        $cursosBase = Curso::query()
            ->where('instructor_id', $instructorId)
            ->with(['tipoCurso'])
            ->withCount([
                'inscripcionesCurso as total_inscritos',
                'inscripcionesCurso as total_aprobados' => fn ($q) => $q->where('aprobado', true),
            ]);

        $cursosActivos = (clone $cursosBase)
            ->where('estado', 'Activo')
            ->orderBy('fecha_inicio')
            ->get();

        $cursoIds = Curso::query()
            ->where('instructor_id', $instructorId)
            ->pluck('id');

        $totalInscripciones = InscripcionCurso::query()
            ->whereIn('curso_id', $cursoIds)
            ->count();

        $inscripcionesAprobadas = InscripcionCurso::query()
            ->whereIn('curso_id', $cursoIds)
            ->where('aprobado', true)
            ->count();

        $this->stats = [
            'total_alumnos' => (int) InscripcionCurso::query()
                ->whereIn('curso_id', $cursoIds)
                ->distinct('feligres_id')
                ->count('feligres_id'),
            'cursos_activos' => (int) $cursosActivos->count(),
            'tasa_aprobacion' => $totalInscripciones > 0
                ? round(($inscripcionesAprobadas / $totalInscripciones) * 100, 1)
                : 0.0,
        ];

        $this->cursosEnCurso = $cursosActivos
            ->take(6)
            ->map(function (Curso $curso) {
                $inscritos = (int) ($curso->total_inscritos ?? 0);
                $aprobados = (int) ($curso->total_aprobados ?? 0);
                $progreso = $this->calcularProgresoCursoPorAprobacion($inscritos, $aprobados);

                return [
                    'id' => (int) $curso->id,
                    'nombre' => (string) $curso->nombre,
                    'tipo' => (string) ($curso->tipoCurso?->nombre_curso ?? 'Curso'),
                    'inscritos' => $inscritos,
                    'aprobados' => $aprobados,
                    'progreso' => $progreso,
                    'progreso_width_class' => $this->resolveProgressWidthClass($progreso),
                ];
            })
            ->values()
            ->all();

        $this->actividadReciente = InscripcionCurso::query()
            ->whereIn('curso_id', $cursoIds)
            ->with(['feligres.persona', 'curso'])
            ->latest('created_at')
            ->take(8)
            ->get()
            ->map(function (InscripcionCurso $inscripcion) {
                $persona = $inscripcion->feligres?->persona;
                $nombre = $persona?->nombre_completo ?: 'Alumno';
                $curso = $inscripcion->curso?->nombre ?: 'Curso';

                return [
                    'momento' => optional($inscripcion->created_at)?->diffForHumans(),
                    'mensaje' => $nombre . ' se inscribio en ' . $curso,
                    'aprobado' => (bool) $inscripcion->aprobado,
                ];
            })
            ->values()
            ->all();

        // Problema #21: Inicializar recursos de capacitación
        $this->recursosCapacitacion = [
            [
                'titulo' => 'Gestión de Cursos',
                'descripcion' => 'Aprende a crear, editar y administrar tus cursos',
                'icono' => 'BookOpen',
                'tipo' => 'video',
            ],
            [
                'titulo' => 'Registro de Alumnos',
                'descripcion' => 'Cómo inscribir y gestionar estudiantes',
                'icono' => 'Users',
                'tipo' => 'guia',
            ],
            [
                'titulo' => 'Generación de Certificados',
                'descripcion' => 'Emite y gestiona certificados de asistencia',
                'icono' => 'Award',
                'tipo' => 'tutorial',
            ],
            [
                'titulo' => 'Preguntas Frecuentes',
                'descripcion' => 'Resuelve dudas comunes sobre el sistema',
                'icono' => 'HelpCircle',
                'tipo' => 'faq',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.instructor.instructor-dashboard');
    }

    private function calcularProgresoCursoPorAprobacion(int $inscritos, int $aprobados): int
    {
        if ($inscritos <= 0) {
            return 0;
        }

        $aprobadosAjustados = max(0, min($aprobados, $inscritos));
        $porcentaje = ($aprobadosAjustados / $inscritos) * 100;

        return (int) round($porcentaje);
    }

    private function resolveProgressWidthClass(int $progreso): string
    {
        return match (true) {
            $progreso <= 0 => 'w-0',
            $progreso <= 8 => 'w-1/12',
            $progreso <= 16 => 'w-2/12',
            $progreso <= 25 => 'w-3/12',
            $progreso <= 33 => 'w-4/12',
            $progreso <= 41 => 'w-5/12',
            $progreso <= 50 => 'w-6/12',
            $progreso <= 58 => 'w-7/12',
            $progreso <= 66 => 'w-8/12',
            $progreso <= 75 => 'w-9/12',
            $progreso <= 83 => 'w-10/12',
            $progreso <= 91 => 'w-11/12',
            default => 'w-full',
        };
    }
}
