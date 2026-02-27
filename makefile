# Define the sail variable
SAIL := ./vendor/bin/sail

# THE FIX: We declare all command-like targets as .PHONY.
.PHONY: help up down restart build-docker destroy ps logs artisan composer npm test build \
        first-run fresh wipe cash clear-log shell tinker queue-work queue-work-sync queue-restart \
        pint pint-dirty

# ===================================================================================================================
#  Help
# ===================================================================================================================

help: ## Show this help message
	@echo "Available commands:"
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

# ===================================================================================================================
#  Docker Container Management
# ===================================================================================================================

up: ## Start the containers in the background
	@$(SAIL) up -d

down: ## Stop the containers
	@$(SAIL) down

restart: down up ## Restart the containers

build-docker: ## Rebuild the containers
	@$(SAIL) build --no-cache

destroy: ## Stop and remove containers, volumes, and networks
	@$(SAIL) down --volumes --remove-orphans

ps: ## Show the status of running containers
	@$(SAIL) ps

logs: ## Follow the container logs in real time
	@$(SAIL) logs -f

# ===================================================================================================================
#  Development Tools & Application
# ===================================================================================================================

artisan: ## Run an artisan command. Example: make artisan ARGS="migrate --seed"
	@$(SAIL) artisan $(ARGS)

composer: ## Run a composer command. Example: make composer ARGS="require laravel/dusk"
	@$(SAIL) composer $(ARGS)

npm: ## Run an npm command. Example: make npm ARGS="install"
	@$(SAIL) npm $(ARGS)

test: ## Run tests
	@$(SAIL) artisan test

build: ## [Frontend] Build frontend assets
	@$(SAIL) npm run build

first-run: ## [App] Run the initial project setup
	@$(SAIL) artisan first-run

fresh: ## [DB] Run migrations with a full database reset
	@$(SAIL) artisan migrate:fresh --seed

wipe: ## [DB] Wipe the database
	@$(SAIL) artisan db:wipe

cash: ## [App] Clear all application caches
	@$(SAIL) artisan cache:clear
	@$(SAIL) artisan config:clear
	@$(SAIL) artisan route:clear
	@$(SAIL) artisan view:clear
	@$(SAIL) artisan event:clear
	@$(SAIL) artisan permission:cache-reset
	@echo "All caches cleared successfully!"

# ===================================================================================================================
#  Queue Management
# ===================================================================================================================

queue-work: ## [Queue] Start a queue worker for the default queue
	@$(SAIL) artisan queue:work -v

queue-work-sync: ## [Queue][SYNC] Start a worker for default and sync queues
	@$(SAIL) artisan queue:work --queue=default,sync -v

queue-restart: ## [Queue] Restart the queue workers
	@$(SAIL) artisan queue:restart

# ===================================================================================================================
#  Shell Access & Logs
# ===================================================================================================================

shell: ## Enter the laravel.test container shell
	@$(SAIL) shell

tinker: ## Run tinker
	@$(SAIL) tinker

clear-log: ## Clear the Laravel log file
	@$(SAIL) artisan log:clear

# ===================================================================================================================
#  Code Styling
# ===================================================================================================================

pint: ## [Style] Format the entire project (PSR-12)
	@$(SAIL) pint

pint-dirty: ## [Style] Format only changed files
	@$(SAIL) pint --dirty
