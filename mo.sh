tx pull -a;

for file in $(find ./languages/ -name *.po -type f);
	do msgfmt "$file" -o "${file%po}mo"; rm "$file";
done
rm "languages/rookie-en.mo";