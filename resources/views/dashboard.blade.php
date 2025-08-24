<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Task Tracker - Dashboard</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            border-radius: 8px;
            margin: 2px 0;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .card-stats {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .card-stats:hover {
            transform: translateY(-5px);
        }
        .btn-action {
            border-radius: 20px;
            padding: 8px 16px;
            font-size: 0.9rem;
        }
        .table-custom th {
            background: #f8f9fa;
            border: none;
            font-weight: 600;
            color: #495057;
        }
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .modal-custom .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <i class="bi bi-kanban text-white" style="font-size: 2rem;"></i>
                        <h5 class="text-white mt-2">Task Tracker</h5>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#dashboard" data-bs-toggle="tab">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#tasks" data-bs-toggle="tab">
                                <i class="bi bi-list-task me-2"></i>
                                Tarefas
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#projects" data-bs-toggle="tab">
                                <i class="bi bi-folder me-2"></i>
                                Projetos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#reports" data-bs-toggle="tab">
                                <i class="bi bi-graph-up me-2"></i>
                                Relatórios
                            </a>
                        </li>
                    </ul>
                    
                    <hr class="text-white-50">
                    
                    <div class="text-center">
                        <div class="text-white-50 mb-2">Logado como:</div>
                        <div class="text-white fw-bold">{{ $collaborator->name ?? 'Usuário' }}</div>
                        <form method="POST" action="{{ route('logout') }}" class="mt-3">
                            @csrf
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-right me-1"></i>Sair
                            </button>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="tab-content">
                    <!-- Dashboard Tab -->
                    <div class="tab-pane fade show active" id="dashboard">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Dashboard</h1>
                            <div class="btn-toolbar mb-2 mb-md-0">
                                <div class="btn-group me-2">
                                    <button type="button" class="btn btn-sm btn-outline-secondary">Exportar</button>
                                </div>
                            </div>
                        </div>

                        <!-- Stats Cards -->
                        <div class="row mb-4">
                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card card-stats border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Tempo Hoje
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $todayTime ?? '00:00' }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="bi bi-clock text-primary" style="font-size: 2rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card card-stats border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Tempo Mês
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $monthTime ?? '00:00' }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="bi bi-calendar text-success" style="font-size: 2rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card card-stats border-left-info shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                                    Tarefas Ativas
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $activeTasks ?? 0 }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="bi bi-list-check text-info" style="font-size: 2rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-3 col-md-6 mb-4">
                                <div class="card card-stats border-left-warning shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                    Projetos
                                                </div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                    {{ $totalProjects ?? 0 }}
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="bi bi-folder text-warning" style="font-size: 2rem;"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Activity -->
                        <div class="row">
                            <div class="col-lg-8">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Atividade Recente</h6>
                                    </div>
                                    <div class="card-body">
                                        @if(isset($recentTimeTrackers) && count($recentTimeTrackers) > 0)
                                            @foreach($recentTimeTrackers as $tracker)
                                                <div class="d-flex align-items-center mb-3">
                                                    <div class="flex-shrink-0">
                                                        <i class="bi bi-clock text-primary"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <div class="fw-bold">{{ $tracker->task->name }}</div>
                                                        <small class="text-muted">
                                                            {{ $tracker->collaborator->name }} - 
                                                            {{ $tracker->start_time->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted text-center">Nenhuma atividade recente</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-lg-4">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Tarefas Pendentes</h6>
                                    </div>
                                    <div class="card-body">
                                        @if(isset($pendingTasks) && count($pendingTasks) > 0)
                                            @foreach($pendingTasks as $task)
                                                <div class="mb-2">
                                                    <span class="badge bg-warning me-2">Pendente</span>
                                                    {{ $task->name }}
                                                </div>
                                            @endforeach
                                        @else
                                            <p class="text-muted text-center">Nenhuma tarefa pendente</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tasks Tab -->
                    <div class="tab-pane fade" id="tasks">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Gerenciar Tarefas</h1>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#taskModal">
                                <i class="bi bi-plus-circle me-1"></i>Nova Tarefa
                            </button>
                        </div>

                        <!-- Filters -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <select class="form-select" id="filterProject">
                                    <option value="">Todos os Projetos</option>
                                    @foreach($projects ?? [] as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filterCollaborator">
                                    <option value="">Todos os Colaboradores</option>
                                    @foreach($collaborators ?? [] as $collaborator)
                                        <option value="{{ $collaborator->id }}">{{ $collaborator->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select class="form-select" id="filterStatus">
                                    <option value="">Todos os Status</option>
                                    <option value="pending">Pendente</option>
                                    <option value="in_progress">Em Progresso</option>
                                    <option value="completed">Concluída</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button class="btn btn-outline-secondary w-100" onclick="applyFilters()">
                                    <i class="bi bi-funnel me-1"></i>Filtrar
                                </button>
                            </div>
                        </div>

                        <!-- Tasks Table -->
                        <div class="card shadow">
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-custom">
                                        <thead>
                                            <tr>
                                                <th>Nome</th>
                                                <th>Projeto</th>
                                                <th>Colaborador</th>
                                                <th>Status</th>
                                                <th>Tempo Total</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tasks ?? [] as $task)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $task->name }}</strong>
                                                        <br><small class="text-muted">{{ $task->description }}</small>
                                                    </td>
                                                    <td>{{ $task->project->name }}</td>
                                                    <td>{{ $task->collaborator->name ?? 'Sem colaborador' }}</td>
                                                    <td>
                                                        <span class="status-badge bg-{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'primary' : 'warning') }}">
                                                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $task->formatted_total_time }}</td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <button class="btn btn-sm btn-outline-primary" onclick="editTask({{ $task->id }})">
                                                                <i class="bi bi-pencil"></i>
                                                            </button>
                                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteTask({{ $task->id }})">
                                                                <i class="bi bi-trash"></i>
                                                            </button>
                                                            @if(!$task->hasActiveTimeTracker())
                                                                <button class="btn btn-sm btn-success" onclick="startTimeTracker({{ $task->id }})">
                                                                    <i class="bi bi-play"></i>
                                                                </button>
                                                            @else
                                                                <button class="btn btn-sm btn-danger" onclick="stopTimeTracker({{ $task->id }})">
                                                                    <i class="bi bi-stop"></i>
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Projects Tab -->
                    <div class="tab-pane fade" id="projects">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Gerenciar Projetos</h1>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#projectModal">
                                <i class="bi bi-plus-circle me-1"></i>Novo Projeto
                            </button>
                        </div>

                        <div class="row">
                            @foreach($projects ?? [] as $project)
                                <div class="col-lg-4 col-md-6 mb-4">
                                    <div class="card h-100 shadow">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <h5 class="card-title">{{ $project->name }}</h5>
                                                <div class="btn-group" role="group">
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editProject({{ $project->id }})">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteProject({{ $project->id }})">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <p class="card-text text-muted">{{ $project->description }}</p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <span class="badge bg-primary">{{ count($project->tasks) }} tarefas</span>
                                                <small class="text-muted">Criado em {{ $project->created_at->format('d/m/Y') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Reports Tab -->
                    <div class="tab-pane fade" id="reports">
                        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                            <h1 class="h2">Relatórios</h1>
                            <div class="d-flex gap-2">
                                <select class="form-select" id="reportCollaborator" style="width: auto;">
                                    <option value="">Todos os Colaboradores</option>
                                    @foreach($collaborators ?? [] as $collaborator)
                                        <option value="{{ $collaborator->id }}">{{ $collaborator->name }}</option>
                                    @endforeach
                                </select>
                                <select class="form-select" id="reportProject" style="width: auto;">
                                    <option value="">Todos os Projetos</option>
                                    @foreach($projects ?? [] as $project)
                                        <option value="{{ $project->id }}">{{ $project->name }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-primary" onclick="generateReport()">
                                    <i class="bi bi-search me-1"></i>Gerar Relatório
                                </button>
                            </div>
                        </div>

                        <div id="reportContent">
                            <!-- Report content will be loaded here -->
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Task Modal -->
    <div class="modal fade modal-custom" id="taskModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalTitle">Nova Tarefa</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="taskForm" method="POST" action="{{ route('tasks.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="taskName" class="form-label">Nome da Tarefa</label>
                            <input type="text" class="form-control" id="taskName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="taskDescription" class="form-label">Descrição</label>
                            <textarea class="form-control" id="taskDescription" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="taskProject" class="form-label">Projeto</label>
                            <select class="form-select" id="taskProject" name="project_id" required>
                                <option value="">Selecione um projeto</option>
                                @foreach($projects ?? [] as $project)
                                    <option value="{{ $project->id }}">{{ $project->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="taskCollaborator" class="form-label">Colaborador</label>
                            <select class="form-select" id="taskCollaborator" name="collaborator_id">
                                <option value="">Sem colaborador</option>
                                @foreach($collaborators ?? [] as $collaborator)
                                    <option value="{{ $collaborator->id }}">{{ $collaborator->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="taskStatus" class="form-label">Status</label>
                            <select class="form-select" id="taskStatus" name="status">
                                <option value="pending">Pendente</option>
                                <option value="in_progress">Em Progresso</option>
                                <option value="completed">Concluída</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Project Modal -->
    <div class="modal fade modal-custom" id="projectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="projectModalTitle">Novo Projeto</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form id="projectForm" method="POST" action="{{ route('projects.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="projectName" class="form-label">Nome do Projeto</label>
                            <input type="text" class="form-control" id="projectName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="projectDescription" class="form-label">Descrição</label>
                            <textarea class="form-control" id="projectDescription" name="description" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Tab navigation
        document.querySelectorAll('.nav-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs
                document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.tab-pane').forEach(t => t.classList.remove('show', 'active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                const target = this.getAttribute('href').substring(1);
                document.getElementById(target).classList.add('show', 'active');
            });
        });

        // Edit task
        function editTask(taskId) {
            // Load task data and populate form
            fetch(`/tasks/${taskId}`)
                .then(response => response.json())
                .then(task => {
                    document.getElementById('taskName').value = task.name;
                    document.getElementById('taskDescription').value = task.description;
                    document.getElementById('taskProject').value = task.project_id;
                    document.getElementById('taskCollaborator').value = task.collaborator_id || '';
                    document.getElementById('taskStatus').value = task.status;
                    document.getElementById('taskModalTitle').textContent = 'Editar Tarefa';
                    document.getElementById('taskForm').action = `/tasks/${taskId}`;
                    document.getElementById('taskForm').innerHTML += '<input type="hidden" name="_method" value="PUT">';
                    new bootstrap.Modal(document.getElementById('taskModal')).show();
                });
        }

        // Edit project
        function editProject(projectId) {
            // Load project data and populate form
            fetch(`/projects/${projectId}`)
                .then(response => response.json())
                .then(project => {
                    document.getElementById('projectName').value = project.name;
                    document.getElementById('projectDescription').value = project.description;
                    document.getElementById('projectModalTitle').textContent = 'Editar Projeto';
                    document.getElementById('projectForm').action = `/projects/${projectId}`;
                    document.getElementById('projectForm').innerHTML += '<input type="hidden" name="_method" value="PUT">';
                    new bootstrap.Modal(document.getElementById('projectModal')).show();
                });
        }

        // Delete functions
        function deleteTask(id) {
            if (confirm('Tem certeza que deseja excluir esta tarefa?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/tasks/${id}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        function deleteProject(id) {
            if (confirm('Tem certeza que deseja excluir este projeto?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/projects/${id}`;
                form.innerHTML = `
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="_method" value="DELETE">
                `;
                document.body.appendChild(form);
                form.submit();
            }
        }

        // Time tracker functions
        function startTimeTracker(taskId) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/time-trackers/start';
            form.innerHTML = `
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="task_id" value="${taskId}">
                <input type="hidden" name="collaborator_id" value="{{ $collaborator->id ?? 1 }}">
            `;
            document.body.appendChild(form);
            form.submit();
        }

        function stopTimeTracker(taskId) {
            // Find the active time tracker for this task
            fetch(`/time-trackers?task_id=${taskId}`)
                .then(response => response.json())
                .then(trackers => {
                    const activeTracker = trackers.data.find(t => !t.end_time);
                    if (activeTracker) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/time-trackers/${activeTracker.id}/stop`;
                        form.innerHTML = `
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        `;
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
        }

        // Filter functions
        function applyFilters() {
            const projectId = document.getElementById('filterProject').value;
            const collaboratorId = document.getElementById('filterCollaborator').value;
            const status = document.getElementById('filterStatus').value;
            
            let url = '/tasks?';
            if (projectId) url += `project_id=${projectId}&`;
            if (collaboratorId) url += `collaborator_id=${collaboratorId}&`;
            if (status) url += `status=${status}&`;
            
            window.location.href = url;
        }

        // Report generation
        function generateReport() {
            const collaboratorId = document.getElementById('reportCollaborator').value;
            const projectId = document.getElementById('reportProject').value;
            
            let url = '/collaborators/report?';
            if (collaboratorId) url += `collaborator_id=${collaboratorId}&`;
            if (projectId) url += `project_id=${projectId}&`;
            
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    displayReport(data.data);
                });
        }

        function displayReport(data) {
            const container = document.getElementById('reportContent');
            let html = '<div class="card shadow"><div class="card-body"><div class="table-responsive">';
            html += '<table class="table table-custom"><thead><tr><th>Colaborador</th><th>Tempo Total</th><th>Detalhes</th></tr></thead><tbody>';
            
            data.forEach(item => {
                html += `<tr><td>${item.collaborator}</td><td>${item.total_time}</td><td>`;
                item.time_trackers.forEach(tracker => {
                    html += `<div class="mb-1">${tracker.task.project.name} - ${tracker.task.name}: ${tracker.formatted_duration}</div>`;
                });
                html += '</td></tr>';
            });
            
            html += '</tbody></table></div></div></div>';
            container.innerHTML = html;
        }

        // Reset modal forms when closed
        document.getElementById('taskModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('taskForm').reset();
            document.getElementById('taskForm').action = '{{ route("tasks.store") }}';
            document.getElementById('taskModalTitle').textContent = 'Nova Tarefa';
            const methodInput = document.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        });

        document.getElementById('projectModal').addEventListener('hidden.bs.modal', function() {
            document.getElementById('projectForm').reset();
            document.getElementById('projectForm').action = '{{ route("projects.store") }}';
            document.getElementById('projectModalTitle').textContent = 'Novo Projeto';
            const methodInput = document.querySelector('input[name="_method"]');
            if (methodInput) methodInput.remove();
        });
    </script>
</body>
</html> 