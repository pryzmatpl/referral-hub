#!/bin/bash

# Directory containing migration files
MIGRATION_DIR="./db/migrations"

# Loop through each PHP file in the migrations directory
for file in "$MIGRATION_DIR"/*.php; do
    # Extract the prefix before the `_` in the filename
    prefix=$(basename "$file" | cut -d'_' -f1)

    # Extract the class name from within the PHP file
    className=$(sed -n 's/.*class\s\+\([A-Za-z0-9_]\+\)\s\+extends.*/\1/p' "$file")

    # Skip the file if the class name was not found
    if [[ -z "$className" ]]; then
        echo "Class name not found in $file. Skipping..."
        continue
    fi

    # Form the new file name with the extracted class name
    newFileName="${prefix}_${className}.php"
    newFilePath="$MIGRATION_DIR/$newFileName"

    # Rename the file if the new name is different
    if [[ "$file" != "$newFilePath" ]]; then
        echo "Renaming $file to $newFilePath"
        mv "$file" "$newFilePath"
    else
        echo "$file is already correctly named."
    fi
done

echo "File renaming completed."
