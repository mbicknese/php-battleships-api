pipeline {
  agent any

  stage('build') {
    steps {
      sh 'docker run \
        -it \
        --rm \
        -v $PWD:/app \
        -v $COMPOSER_HOME:/tmp \
        composer install -n -o'
    }
  },
  stage('test') {
    steps {
      sh 'docker run \
        -it \
        --rm \
        -v $PWD:/app \
        -w /app \
        php -f vendor/bin/phpunit'
    }
  },
  stage('deploy') {
    echo "No deployment setup at the moment.."
  }
}
