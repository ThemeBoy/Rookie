echo "Enter version number."
read version

mkdir ../../archives/rookie/$version

zip -r ../../archives/rookie/$version/rookie.zip . -x "*.DS_Store" ".tx/*" "*.sh"