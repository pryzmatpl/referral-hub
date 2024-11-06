for file in ./*; do
  prefix=$(date +%Y%m%d%H%M%S)

  if [ -f "$file" ]; then
    filename=$(basename "$file")
    mv "$file" "./${prefix}_$filename"
  fi
done
