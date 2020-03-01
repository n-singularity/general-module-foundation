git init
git add .
git remote add origin https://github.com/n-singularity/general-module-foundation.git
git fetch
git checkout -b 'temp'
git branch -D master
git checkout -b master origin/master
git checkout temp
git checkout rebase master
git checkout master
git rebase temp
