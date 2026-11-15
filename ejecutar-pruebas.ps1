$ErrorActionPreference = 'Stop'

Write-Host "`n======================================" -ForegroundColor Cyan
Write-Host " EJECUTANDO PRUEBAS CON PHPUNIT"
Write-Host "======================================`n" -ForegroundColor Cyan

if (Test-Path '.\vendor\bin\phpunit.bat') {
    & '.\vendor\bin\phpunit.bat' --configuration '.\phpunit.xml'
} elseif (Test-Path '.\vendor\bin\phpunit') {
    & '.\vendor\bin\phpunit' --configuration '.\phpunit.xml'
} elseif (Test-Path '.\tools\phpunit.phar') {
    php '.\tools\phpunit.phar' --configuration '.\phpunit.xml'
} else {
    Write-Host "No se encontró PHPUnit. Instálalo con Composer o agrega tools\phpunit.phar." -ForegroundColor Red
    exit 1
}

if ($LASTEXITCODE -ne 0) {
    Write-Host "`nERROR: Fallaron las pruebas PHPUnit." -ForegroundColor Red
    exit $LASTEXITCODE
}

Write-Host "`n======================================" -ForegroundColor Cyan
Write-Host " EJECUTANDO PRUEBAS PERSONALIZADAS"
Write-Host "======================================`n" -ForegroundColor Cyan

php '.\tests\run.php'

if ($LASTEXITCODE -ne 0) {
    Write-Host "`nERROR: Fallaron las pruebas personalizadas." -ForegroundColor Red
    exit $LASTEXITCODE
}

Write-Host "`n======================================" -ForegroundColor Green
Write-Host " TODAS LAS PRUEBAS FINALIZARON BIEN"
Write-Host "======================================`n" -ForegroundColor Green
