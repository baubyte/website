#!/bin/bash

# Script de desarrollo para el proyecto Baubyte Website
# Facilita el manejo del entorno de desarrollo con Docker

set -e

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Función para mostrar ayuda
show_help() {
    echo -e "${BLUE}🚀 Script de Desarrollo - Baubyte Website${NC}"
    echo ""
    echo "Uso: ./dev.sh [comando]"
    echo ""
    echo "Comandos disponibles:"
    echo -e "  ${GREEN}start${NC}     - Iniciar el entorno de desarrollo"
    echo -e "  ${GREEN}stop${NC}      - Detener el entorno de desarrollo"
    echo -e "  ${GREEN}restart${NC}   - Reiniciar el entorno de desarrollo"
    echo -e "  ${GREEN}build${NC}     - Construir la imagen de desarrollo"
    echo -e "  ${GREEN}rebuild${NC}   - Reconstruir completamente la imagen"
    echo -e "  ${GREEN}logs${NC}      - Ver logs del contenedor"
    echo -e "  ${GREEN}shell${NC}     - Acceder al shell del contenedor"
    echo -e "  ${GREEN}status${NC}    - Ver estado del contenedor"
    echo -e "  ${GREEN}clean${NC}     - Limpiar contenedores e imágenes no utilizadas"
    echo -e "  ${GREEN}help${NC}      - Mostrar esta ayuda"
    echo ""
    echo "Ejemplos:"
    echo "  ./dev.sh start   # Inicia el entorno en http://localhost:8080"
    echo "  ./dev.sh logs    # Muestra logs en tiempo real"
    echo "  ./dev.sh shell   # Accede al contenedor para debugging"
}

# Función para verificar si Docker está ejecutándose
check_docker() {
    if ! docker info > /dev/null 2>&1; then
        echo -e "${RED}❌ Error: Docker no está ejecutándose${NC}"
        exit 1
    fi
}

# Función para iniciar el entorno
start_dev() {
    echo -e "${BLUE}🚀 Iniciando entorno de desarrollo...${NC}"
    check_docker
    
    # Verificar si el archivo .env existe
    if [ ! -f ".env" ]; then
        echo -e "${YELLOW}⚠️  El archivo .env no existe. Creando uno básico...${NC}"
        cp .env.example .env 2>/dev/null || echo "# Archivo .env para desarrollo" > .env
    fi
    
    # Crear directorio writable si no existe
    mkdir -p writable/{cache,logs,session,uploads}
    chmod -R 755 writable/
    
    # Iniciar servicios
    docker compose -f docker-compose.dev.yaml up -d
    
    echo -e "${GREEN}✅ Entorno de desarrollo iniciado!${NC}"
    echo -e "${BLUE}🌐 Aplicación disponible en: http://localhost:8080${NC}"
    echo -e "${YELLOW}💡 Para ver logs: ./dev.sh logs${NC}"
}

# Función para detener el entorno
stop_dev() {
    echo -e "${YELLOW}🛑 Deteniendo entorno de desarrollo...${NC}"
    docker compose -f docker-compose.dev.yaml down
    echo -e "${GREEN}✅ Entorno detenido${NC}"
}

# Función para reiniciar el entorno
restart_dev() {
    echo -e "${BLUE}🔄 Reiniciando entorno de desarrollo...${NC}"
    stop_dev
    start_dev
}

# Función para construir la imagen
build_dev() {
    echo -e "${BLUE}🔨 Construyendo imagen de desarrollo...${NC}"
    check_docker
    docker compose -f docker-compose.dev.yaml build
    echo -e "${GREEN}✅ Imagen construida${NC}"
}

# Función para reconstruir completamente
rebuild_dev() {
    echo -e "${BLUE}🔨 Reconstruyendo imagen completamente...${NC}"
    check_docker
    docker compose -f docker-compose.dev.yaml build --no-cache
    echo -e "${GREEN}✅ Imagen reconstruida${NC}"
}

# Función para ver logs
logs_dev() {
    echo -e "${BLUE}📋 Mostrando logs...${NC}"
    docker compose -f docker-compose.dev.yaml logs -f
}

# Función para acceder al shell
shell_dev() {
    echo -e "${BLUE}🐚 Accediendo al contenedor...${NC}"
    docker compose -f docker-compose.dev.yaml exec baubyte-website-dev bash
}

# Función para ver estado
status_dev() {
    echo -e "${BLUE}📊 Estado del entorno de desarrollo:${NC}"
    docker compose -f docker-compose.dev.yaml ps
}

# Función para limpiar
clean_dev() {
    echo -e "${YELLOW}🧹 Limpiando contenedores e imágenes no utilizadas...${NC}"
    docker compose -f docker-compose.dev.yaml down
    docker system prune -f
    echo -e "${GREEN}✅ Limpieza completada${NC}"
}

# Procesar argumentos
case "${1:-help}" in
    start)
        start_dev
        ;;
    stop)
        stop_dev
        ;;
    restart)
        restart_dev
        ;;
    build)
        build_dev
        ;;
    rebuild)
        rebuild_dev
        ;;
    logs)
        logs_dev
        ;;
    shell)
        shell_dev
        ;;
    status)
        status_dev
        ;;
    clean)
        clean_dev
        ;;
    help|--help|-h)
        show_help
        ;;
    *)
        echo -e "${RED}❌ Comando no reconocido: $1${NC}"
        echo ""
        show_help
        exit 1
        ;;
esac
