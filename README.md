# Task Tracker - Sistema de Controle de Tarefas com Apontamento de Horas

Sistema completo para controle de tarefas com apontamento de horas, desenvolvido em Laravel com interface moderna e responsiva.

## 🚀 Funcionalidades

### ✅ Funcionalidades Principais
- **Cadastro de Tasks (CRUD)** - Gerenciamento completo de tarefas
- **Cadastro de Projetos (CRUD)** - Organização de tarefas por projeto
- **Interface para listagem de tarefas** - Visualização organizada e filtros
- **Visualização do tempo gasto no dia corrente** - Formato Horas:Minutos
- **Visualização do tempo gasto no mês corrente** - Formato Horas:Minutos
- **Associação colaborador a uma task** - Atribuição de responsabilidades
- **Apontamento de tempo gasto nas tasks** - Controle preciso de horas
- **DataHora inicial e DataHora final** - Registro temporal completo

### 🔐 Autenticação e Segurança
- **Autenticação JWT** - Sistema seguro de login
- **Validação de campos** - Frontend e backend
- **Controle de acesso** - Rotas protegidas
- **Senhas criptografadas** - Segurança máxima

### 📊 Relatórios e Controles
- **Relatórios por colaborador** - Análise individual
- **Relatórios por projeto** - Análise por projeto
- **Relatório diário** - Visualização dia-a-dia
- **Filtros avançados** - Busca personalizada
- **Controle de conflitos de tempo** - Evita sobreposições
- **Limite de 24h por dia** - Controle de jornada

### 🎨 Interface
- **Design responsivo** - Funciona em todos os dispositivos
- **Interface moderna** - Tailwind CSS + Vue.js
- **UX intuitiva** - Fácil de usar
- **Feedback visual** - Mensagens claras de erro/sucesso

## 🛠️ Tecnologias Utilizadas

- **Backend**: Laravel 12, PHP 8.2+
- **Frontend**: Vue.js 3, Tailwind CSS
- **Banco de Dados**: SQLite (configurável para MySQL/PostgreSQL)
- **Autenticação**: JWT (tymon/jwt-auth)
- **Validação**: Laravel Validator
- **ORM**: Eloquent

## 📋 Pré-requisitos

- PHP 8.2 ou superior
- Composer
- Node.js e NPM
- Git

## 🚀 Instalação

### 1. Clone o repositório
```bash
git clone <url-do-repositorio>
cd task-tracker
```

### 2. Instale as dependências PHP
```bash
composer install
```

### 3. Instale as dependências Node.js
```bash
npm install
```

### 4. Configure o ambiente
```bash
cp .env.example .env
```

Edite o arquivo `.env` e configure:
```env
APP_NAME="Task Tracker"
APP_KEY=base64:sua-chave-aqui
DB_CONNECTION=sqlite
DB_DATABASE=database/database.sqlite
JWT_SECRET=sua-chave-jwt-aqui
```

### 5. Gere as chaves da aplicação
```bash
php artisan key:generate
php artisan jwt:secret
```

### 6. Execute as migrações
```bash
php artisan migrate
```

### 7. Execute o seeder para dados iniciais
```bash
php artisan db:seed
```

### 8. Compile os assets
```bash
npm run build
```

### 9. Inicie o servidor
```bash
php artisan serve
```

## 🔑 Credenciais de Teste

O sistema vem com usuários pré-cadastrados para teste:

- **Usuário**: `dev1` | **Senha**: `password123`
- **Usuário**: `dev2` | **Senha**: `password123`

## 📱 Como Usar

### 1. Login
- Acesse a aplicação
- Use as credenciais de teste
- Sistema redireciona para o dashboard

### 2. Dashboard
- **Cards de estatísticas**: Tempo hoje, mês e tarefas ativas
- **Abas organizadas**: Tarefas, Projetos e Relatórios

### 3. Gerenciar Tarefas
- Visualize todas as tarefas
- Filtre por projeto, colaborador ou status
- Crie, edite e exclua tarefas
- Inicie/pare o cronômetro de tempo

### 4. Gerenciar Projetos
- Crie e organize projetos
- Visualize tarefas associadas
- Edite informações dos projetos

### 5. Relatórios
- Gere relatórios por período
- Filtre por colaborador ou projeto
- Visualize tempo gasto dia-a-dia

## 🏗️ Estrutura do Banco de Dados

### Tabelas Principais
- **users** - Usuários do sistema
- **collaborators** - Colaboradores com credenciais
- **projects** - Projetos organizacionais
- **tasks** - Tarefas associadas a projetos
- **time_trackers** - Registros de tempo das tarefas

### Relacionamentos
- User ↔ Collaborator (1:1)
- Project ↔ Tasks (1:N)
- Task ↔ TimeTrackers (1:N)
- Collaborator ↔ Tasks (1:N)
- Collaborator ↔ TimeTrackers (1:N)

## 🔒 Regras de Negócio Implementadas

- ✅ Username único para colaboradores
- ✅ Senhas criptografadas com Hash
- ✅ Vinculo forte entre User e Collaborator
- ✅ Validação de conflitos de tempo
- ✅ Limite de 24h por dia
- ✅ Task obrigatoriamente associada a projeto
- ✅ Colaborador opcional para task
- ✅ Validação de campos frontend/backend
- ✅ API REST funcional
- ✅ Autenticação JWT
- ✅ Interface responsiva
- ✅ Tratamento de erros claro

## 📊 API Endpoints

### Autenticação
- `POST /auth/login` - Login
- `POST /auth/logout` - Logout
- `POST /auth/refresh` - Renovar token
- `GET /auth/me` - Dados do usuário

### Projetos
- `GET /projects` - Listar projetos
- `POST /projects` - Criar projeto
- `GET /projects/{id}` - Ver projeto
- `PUT /projects/{id}` - Atualizar projeto
- `DELETE /projects/{id}` - Excluir projeto

### Tarefas
- `GET /tasks` - Listar tarefas (com filtros)
- `POST /tasks` - Criar tarefa
- `GET /tasks/{id}` - Ver tarefa
- `PUT /tasks/{id}` - Atualizar tarefa
- `DELETE /tasks/{id}` - Excluir tarefa

### Time Trackers
- `GET /time-trackers` - Listar registros
- `POST /time-trackers` - Criar registro
- `POST /time-trackers/start` - Iniciar cronômetro
- `POST /time-trackers/{id}/stop` - Parar cronômetro
- `PUT /time-trackers/{id}` - Atualizar registro
- `DELETE /time-trackers/{id}` - Excluir registro

### Colaboradores
- `GET /collaborators` - Listar colaboradores
- `POST /collaborators` - Criar colaborador
- `GET /collaborators/{id}` - Ver colaborador
- `PUT /collaborators/{id}` - Atualizar colaborador
- `DELETE /collaborators/{id}` - Excluir colaborador
- `GET /collaborators/{id}/report` - Relatório do colaborador
- `GET /collaborators/{id}/daily-report` - Relatório diário

## 🧪 Testes

Execute os testes com:
```bash
php artisan test
```

## 🚀 Deploy

### Produção
1. Configure o banco de dados de produção
2. Ajuste as variáveis de ambiente
3. Execute `npm run build`
4. Configure o servidor web (Apache/Nginx)
5. Configure o supervisor para filas (se necessário)

### Docker (opcional)
```bash
docker-compose up -d
```

## 🤝 Contribuição

1. Fork o projeto
2. Crie uma branch para sua feature
3. Commit suas mudanças
4. Push para a branch
5. Abra um Pull Request

## 📝 Licença

Este projeto está sob a licença MIT. Veja o arquivo `LICENSE` para mais detalhes.

## 📞 Suporte

Para dúvidas ou suporte, abra uma issue no repositório.

---

**Desenvolvido com ❤️ usando Laravel e Vue.js**
