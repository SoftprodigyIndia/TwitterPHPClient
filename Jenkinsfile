pipeline {
  agent any
  stages {
    stage('build') {
      agent any
      environment {
        test = 'test'
      }
      steps {
        git(changelog: true, url: 'https://github.com/SoftprodigyIndia/TwitterPHPClient', branch: 'master')
      }
    }
    stage('error') {
      agent any
      environment {
        test = 'test'
      }
      steps {
        ws(dir: 'ppp') {
          build(job: 'pppp', propagate: true, wait: true, quietPeriod: 1)
        }

        tool(name: 'jjj', type: 'jjj')
      }
    }
  }
  environment {
    ll = '000'
  }
}