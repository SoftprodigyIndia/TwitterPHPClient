pipeline {
  agent any
  stages {
    stage('build') {
      agent {
        docker {
          image 'dockerfile/ubuntu'
        }

      }
      environment {
        test = 'test'
      }
      steps {
        git(changelog: true, url: 'https://github.com/softprodigyofficial/NCGPA.git', branch: 'develop')
      }
    }
  }
  environment {
    ll = '000'
  }
}