pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: 'https://github.com/RJosoa/blendskk.git'
            }
        }

        stage('Build & Deploy') {
            steps {
                sh 'cp .env.example .env'
                sh 'docker compose down'
                sh 'docker compose up -d --build'
            }
        }
    }
}
