pipeline {
    agent any

    environment {
        GIT_REPO = "https://github.com/RJosoa/blendskk.git"
        GIT_BRANCH = "main"
        DEPLOY_DIR = "/var/www/blendsk"
    }

    stages {
        stage('Cloner le dépôt') {
            steps {
                sh "rm -rf ${DEPLOY_DIR}" // Nettoyage du précédent build
                sh "git clone -b ${GIT_BRANCH} ${GIT_REPO} ${DEPLOY_DIR}"
            }
        }

        stage('Construire et Lancer Docker') {
            steps {
                dir("${DEPLOY_DIR}") {
                    sh 'docker compose down'
                    sh 'docker compose up -d --build'
                }
            }
        }

        stage('Configurer l’environnement') {
            steps {
                script {
                    def envLocal = """
                    APP_ENV=prod
                    APP_DEBUG=0
                    DATABASE_URL=mysql://root:root@database:3306/blendsk?serverVersion=8.0&charset=utf8mb4
                    """.stripIndent()

                    writeFile file: "${DEPLOY_DIR}/.env.local", text: envLocal
                }
            }
        }

        stage('Migration de la base de données') {
            steps {
                dir("${DEPLOY_DIR}") {
                    sh 'docker exec blendsk_app php bin/console doctrine:database:create --if-not-exists --env=prod'
                    sh 'docker exec blendsk_app php bin/console doctrine:migrations:migrate --no-interaction --env=prod'
                }
            }
        }

        stage('Nettoyage du cache') {
            steps {
                dir("${DEPLOY_DIR}") {
                    sh 'docker exec blendsk_app php bin/console cache:clear --env=prod'
                    sh 'docker exec blendsk_app php bin/console cache:warmup'
                }
            }
        }

        stage('Déploiement sur le serveur') {
            steps {
                sh "docker cp ${DEPLOY_DIR} blendsk_app:/var/www/blendsk"
                sh "docker exec blendsk_app chmod -R 775 /var/www/blendsk/var"
            }
        }
    }

    post {
        success {
            echo '✅ Déploiement réussi !'
        }
        failure {
            echo '❌ Erreur lors du déploiement.'
        }
    }
}
