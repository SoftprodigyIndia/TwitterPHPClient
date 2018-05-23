pipeline {
  agent {
    dockerfile {
      filename 'dockerfile/ubuntu'
    }

  }
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
    stage('error') {
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