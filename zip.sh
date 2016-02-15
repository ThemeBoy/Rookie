echo "Enter version number."
read version

zip -r ../../archives/rookie.$version.zip . -x "*.DS_Store" ".tx/*" "*.sh"