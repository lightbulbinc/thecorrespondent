# check status
```
git status
```
# difference with server version
```
git diff master <local file name (with path)>
```
Keep pressing `PgDn` / `PgUp` to navigate down and up the changes.

When you see the `(END)`, exit this view, press `q`.
# stage file(s)
## all files
### If you are only adding / updating files
```
git add .
```
### If you are adding / updating *and deleting* files
```
git add -A
```
## single file
```
git add <file name (with path)>
```
# unstage a file
```
git reset HEAD <file name (with path)>
```
# commit files
```
git commit -m "comment"
```
# push to git repo
```
git push
```
