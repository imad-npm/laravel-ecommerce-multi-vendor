import os

# Fonction pour vérifier si un fichier doit être inclus
def is_valid_file(file_path):
    # On exclut les répertoires vendor, node_modules, storage, bootstrap, test et config
    if any(excluded in file_path for excluded in ['vendor', 'node_modules', 'storage', 'bootstrap', 'test', 'config']):
        return False
    # On ne compte que les fichiers .php et .blade.php
    if file_path.endswith('.php') or file_path.endswith('.blade.php'):
        return True
    return False

# Fonction pour compter les lignes de code dans un fichier (sans compter les lignes vides)
def count_lines(file_path):
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as file:
        # Compter les lignes non vides
        return sum(1 for line in file if line.strip())

# Fonction principale pour parcourir le projet Laravel
def count_loc_in_laravel_project(root_dir):
    total_lines = 0
    for dirpath, dirnames, filenames in os.walk(root_dir):
        # Exclure les répertoires vendor, node_modules, storage, bootstrap, test et config
        if any(excluded in dirpath for excluded in ['vendor', 'node_modules', 'storage', 'bootstrap', 'test', 'config']):
            continue
        
        for filename in filenames:
            file_path = os.path.join(dirpath, filename)
            
            if is_valid_file(file_path):
                lines_in_file = count_lines(file_path)
                total_lines += lines_in_file
                print(f"{file_path}: {lines_in_file} lignes")

    return total_lines

# Point d'entrée du script
if __name__ == "__main__":
    root_directory = input("Entrez le chemin de votre projet Laravel : ")
    total_loc = count_loc_in_laravel_project(root_directory)
    print(f"\nTotal des lignes de code dans les fichiers .php et .blade.php (sans lignes vides) : {total_loc} lignes.")