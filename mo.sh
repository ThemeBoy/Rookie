tx pull -a;

for file in $(find ./languages/ -name *.po -type f);
	do msgfmt "$file" -o "${file%po}mo";
done

rm "languages/rookie-en.po";
rm "languages/rookie-en.mo";