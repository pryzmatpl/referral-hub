
#!/bin/bash

DEPLOY_SERVER='root@ec2-18-185-73-100.eu-central-1.compute.amazonaws.com'
CERTPATH='/home/piotro/refairmeeustage.pem'

echo "Deploy project on server ${DEPLOY_SERVER}"    
ssh ${DEPLOY_SERVER} -i ${CERTPATH} "cd /usr/share/nginx/refairme && git pull origin stage" 
