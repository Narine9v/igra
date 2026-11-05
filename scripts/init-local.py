"""Create local database passwords without printing secrets or overwriting settings."""
from pathlib import Path
import secrets
path = Path(__file__).resolve().parents[1] / '.env'
if not path.exists():
    path.touch(mode=0o600, exist_ok=False)
    path.write_text('DB_PASSWORD=' + secrets.token_hex(24) + '\nDB_ROOT_PASSWORD=' + secrets.token_hex(24) + '\n')
theme = path.parent / 'theme'
for item in [theme, *theme.rglob('*')]:
    item.chmod(0o755 if item.is_dir() else 0o644)
print('Local configuration is present; values are not displayed.')
