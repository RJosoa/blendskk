pipeline {
    agent any

    stages {
        stage('Checkout') {
            steps {
                git 'https://github.com/RJosoa/blendskk.git'
            }
        }

        stage('Build & Deploy') {
            steps {
                sh 'docker compose down'
                sh 'docker compose up -d --build'
            }
        }
    }
}
