git init
git add .
git commit -m 'temp'
git remote add origin https://github.com/n-singularity/general-module-foundation.git
git fetch
git checkout -b 'temp'
git branch -D master
git checkout -b master origin/master
git checkout temp
git rebase master
git checkout master
git rebase temp
git push origin master
