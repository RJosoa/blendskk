pipeline {
    agent any

    environment {
        APP_PATH = "/var/www/blendsk"
    }

    stages {
        stage('Checkout Code') {
            steps {
                git branch: 'main', url: 'https://github.com/tonrepo/blendsk.git'
            }
        }

        stage('Stop Application') {
            steps {
                sh 'docker-compose down'
            }
        }

        stage('Update Code') {
            steps {
                sh 'rm -rf $APP_PATH/*'
                sh 'cp -r * $APP_PATH/'
            }
        }

        stage('Run Composer Install') {
            steps {
                sh 'cd $APP_PATH && composer install --no-interaction --prefer-dist'
            }
        }

        stage('Restart Application') {
            steps {
                sh 'docker-compose up -d'
            }
        }

        stage('Check Application Status') {
            steps {
                sh 'docker ps'
            }
        }
    }
}
