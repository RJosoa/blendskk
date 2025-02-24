pipeline {
    agent any

    environment {
        APP_PATH = "/var/www/blendsk"
    }

    stages {
        stage('Checkout Code') {
            steps {
                git credentialsId: 'github-token', branch: 'main', url: 'https://github.com/RJosoa/blendskk.git'
            }
        }

        stage('Stop Application') {
            steps {
                sh 'docker compose down'
            }
        }

        stage('Update Code') {
            steps {
                sh 'mkdir -p /var/www/blendsk/'
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
                sh 'docker compose up -d'
            }
        }

        stage('Check Application Status') {
            steps {
                sh 'docker ps'
            }
        }
    }
}


pipeline {
  agent any
  environment {
    DOCKER_IMAGE = 'josoa/blendsk'
    DOCKER_TAG   = 'latest'

    COMPOSE_FILE = '/var/www/blendsk/compose.yaml'
  }
  stages {
    stage('Checkout') {
      steps {
        git credentialsId: 'github-token', branch: 'main', url: 'https://github.com/RJosoa/blendskk.git'
        checkout scm
      }
    }
    stage('Build Docker Image') {
      steps {
        sh "docker build -t ${DOCKER_IMAGE}:${DOCKER_TAG} -f Dockerfile ."
      }
    }
    stage('Deploy') {
      steps {
        sh "docker compose -f ${COMPOSE_FILE} down"
        sh "docker compose -f ${COMPOSE_FILE} up -d"
      }
    }
  }
  post {
    success {
      echo 'Déploiement effectué avec succès.'
    }
    failure {
      echo 'Échec du déploiement. Consultez les logs pour plus d\'informations.'
    }
  }
}
