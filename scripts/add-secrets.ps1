#!/usr/bin/env pwsh
#!/usr/bin/env pwsh
<#
Script: add-secrets.ps1
But: collecte interactive des valeurs sensibles et crée des secrets GitHub pour le repo courant via `gh`.
Usage: exécuter depuis la racine du dépôt: .\scripts\add-secrets.ps1
#>

function Read-SecurePlainText {
    param(
        [string]$Prompt
    )
    $secure = Read-Host -Prompt $Prompt -AsSecureString
    if ($secure -eq $null) { return "" }
    $ptr = [Runtime.InteropServices.Marshal]::SecureStringToBSTR($secure)
    try { [Runtime.InteropServices.Marshal]::PtrToStringAuto($ptr) }
    finally { [Runtime.InteropServices.Marshal]::ZeroFreeBSTR($ptr) }
}

Write-Host "Vérification: 'gh' est installé et authentifié..." -ForegroundColor Cyan
$ghStatus = gh auth status 2>&1
if ($LASTEXITCODE -ne 0) {
    Write-Host "Erreur: 'gh' n'est pas authentifié. Exécutez 'gh auth login' puis relancez ce script." -ForegroundColor Red
    Write-Host $ghStatus
    exit 1
}

# Repo détecté par défaut (remote 'origin' ou 'joy' si présent)
$repo = gh repo view --json nameWithOwner -q .nameWithOwner 2>$null
if (-not $repo) {
    Write-Host "Impossible de déterminer le repo automatiquement." -ForegroundColor Yellow
    $repo = Read-Host -Prompt "Indiquez le repo GitHub (ex: joynitalegba07-eng/mini_amazone)"
}
Write-Host "Repo cible : $repo" -ForegroundColor Green

# Collecte des variables
$ftpHost = Read-Host -Prompt "FTP_HOST (ex: ftp.alwaysdata.com)"
$ftpUser = Read-Host -Prompt "FTP_USERNAME"
$ftpPass = Read-SecurePlainText "FTP_PASSWORD (saisie sécurisée)"
$ftpDir  = Read-Host -Prompt "FTP_TARGET_DIR (ex: /www/monsite)"

$sshHost = Read-Host -Prompt "SSH_HOST (ex: ssh.alwaysdata.net)"
$sshUser = Read-Host -Prompt "SSH_USER (ex: monuser@alwaysdata)"
$sshPort = Read-Host -Prompt "SSH_PORT (default 22)"
if (-not $sshPort) { $sshPort = 22 }

# Check or generate SSH key
$sshDir = Join-Path $env:USERPROFILE ".ssh"
if (-not (Test-Path $sshDir)) { New-Item -ItemType Directory -Path $sshDir | Out-Null }

# Prefer ed25519
$keyPath = Join-Path $sshDir "id_ed25519"
if (-not (Test-Path $keyPath)) {
    Write-Host "Aucune clé SSH id_ed25519 trouvée - génération d'une nouvelle paire (sans passphrase) ..." -ForegroundColor Yellow
    ssh-keygen -t ed25519 -f $keyPath -N "" -C "deploy@$(whoami)-$(Get-Date -Format yyyyMMdd)" | Out-Null
    Write-Host "Clé générée : $keyPath" -ForegroundColor Green
    Write-Host "Voici la clé publique à ajouter dans Alwaysdata -> SSH Keys :" -ForegroundColor Cyan
    Get-Content "$keyPath.pub" -Raw
    Write-Host "-- Attendez d'avoir ajouté la clé publique dans Alwaysdata, puis appuyez sur Entrée pour continuer." -ForegroundColor Yellow
    Read-Host -Prompt "Appuyez sur Entrée une fois ajouté"
} else {
    Write-Host "Clé SSH existante trouvée : $keyPath" -ForegroundColor Green
}

# Récupérer la clé privée
$privateKey = Get-Content $keyPath -Raw
if (-not $privateKey) {
    Write-Host "Erreur: impossible de lire la clé privée." -ForegroundColor Red
    exit 1
}

# Fonction pour définir un secret via gh
function Set-SecretIfValue {
    param(
        [string]$name,
        [string]$value
    )
    if ([string]::IsNullOrEmpty($value)) {
        Write-Host "Valeur vide pour $name -- saut." -ForegroundColor Yellow
        return
    }
    Write-Host "Définition du secret $name ..." -ForegroundColor Cyan
    gh secret set $name --body "$value" --repo $repo
    if ($LASTEXITCODE -ne 0) { Write-Host "Erreur lors de la définition de $name" -ForegroundColor Red }
}

# Créer les secrets
Set-SecretIfValue -name "FTP_HOST" -value $ftpHost
Set-SecretIfValue -name "FTP_USERNAME" -value $ftpUser
Set-SecretIfValue -name "FTP_PASSWORD" -value $ftpPass
Set-SecretIfValue -name "FTP_TARGET_DIR" -value $ftpDir
Set-SecretIfValue -name "SSH_HOST" -value $sshHost
Set-SecretIfValue -name "SSH_USER" -value $sshUser
Set-SecretIfValue -name "SSH_PORT" -value $sshPort
Set-SecretIfValue -name "SSH_PRIVATE_KEY" -value $privateKey

Write-Host "Liste des secrets actuels pour $repo :" -ForegroundColor Green
gh secret list --repo $repo

Write-Host "Terminé. Le workflow de déploiement peut maintenant être lancé (ou attendre que push se produise)." -ForegroundColor Green
