pipeline {
    agent any
    environment {
        COMPOSE_FILE = 'compose.yaml'
    }
    stages {
        stage('Checkout') {
            steps {
                // Remplacez l'URL par celle de votre dépôt
                 git credentialsId: 'github-token', branch: 'main', url: 'https://github.com/RJosoa/blendskk.git'
            }
        }
        stage('Déploiement') {
            steps {
                script {
                    // Arrête et supprime les containers existants
                    sh 'docker-compose down'
                    // Démarre les containers en arrière-plan
                    sh 'docker-compose up -d'
                }
            }
        }
        stage('Installation des dépendances') {
            steps {
                // Exécute composer install dans le container "app"
                sh 'docker-compose exec app composer install --no-dev --optimize-autoloader'
            }
        }
    }
    post {
        always {
            echo "Pipeline de déploiement terminé."
        }
    }
}
