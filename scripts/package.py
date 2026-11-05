from pathlib import Path
from zipfile import ZipFile, ZIP_DEFLATED
root = Path(__file__).resolve().parents[1]
(root / 'dist').mkdir(exist_ok=True)
with ZipFile(root / 'dist/zerno-very.zip', 'w', ZIP_DEFLATED) as archive:
    for path in sorted((root / 'theme/zerno-very').rglob('*')):
        if path.is_file():
            archive.write(path, path.relative_to(root / 'theme'))
print('Created dist/zerno-very.zip')
