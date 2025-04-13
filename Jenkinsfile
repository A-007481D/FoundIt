pipeline {
    agent any

    environment {
        DB_CONNECTION = 'pgsql'
        DB_HOST = '127.0.0.1'
        DB_PORT = '5432'
        DB_DATABASE = 'foundit_test'
        DB_USERNAME = 'postgres'
        DB_PASSWORD = 'postgres'
    }

    stages {
        stage('Checkout') {
            steps {
                checkout scm
            }
        }

        stage('Start Database') {
            steps {
                sh '''
                    docker run --rm -d \
                      --name test-postgres \
                      -e POSTGRES_USER=$DB_USERNAME \
                      -e POSTGRES_PASSWORD=$DB_PASSWORD \
                      -e POSTGRES_DB=$DB_DATABASE \
                      -p 5432:5432 \
                      postgres:15
                    sleep 10
                '''
            }
        }

        stage('Install Dependencies') {
            steps {
                dir('backend') {
                    sh 'composer install --prefer-dist --no-progress --no-suggest'
                }
            }
        }

        stage('Setup Laravel') {
            steps {
                dir('backend') {
                    sh '''
                        echo "APP_ENV=testing" > .env
                        echo "APP_KEY=" >> .env
                        echo "APP_DEBUG=false" >> .env
                        echo "DB_CONNECTION=$DB_CONNECTION" >> .env
                        echo "DB_HOST=$DB_HOST" >> .env
                        echo "DB_PORT=$DB_PORT" >> .env
                        echo "DB_DATABASE=$DB_DATABASE" >> .env
                        echo "DB_USERNAME=$DB_USERNAME" >> .env
                        echo "DB_PASSWORD=$DB_PASSWORD" >> .env

                        php artisan key:generate
                    '''
                }
            }
        }

        stage('Migrate') {
            steps {
                dir('backend') {
                    sh 'php artisan migrate --force'
                }
            }
        }

        stage('Run Tests') {
            steps {
                dir('backend') {
                    sh 'php artisan test'
                }
            }
        }
    }

    post {
        always {
            echo "🧼 Cleaning up..."
            sh 'docker stop test-postgres || true'
        }
    }
}
