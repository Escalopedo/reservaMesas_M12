# Dinner At Westfield - Documentación del Proyecto

## Descripción del Proyecto

**Dinner At Westfield** es una aplicación web diseñada para la gestión eficiente de un restaurante. La web permite realizar reservas de mesas, gestionar recursos como camareros y mobiliario, y administrar usuarios con diferentes roles, como gerente, personal de mantenimiento, y camareros. 

El sistema incluye tanto funcionalidades dinámicas en la parte del cliente, como un backend robusto que asegura la interacción eficiente con la base de datos. Además, la interfaz está diseñada para ofrecer una experiencia de usuario homogénea y visualmente atractiva.

---

## Funcionalidades Principales

### 1. **Gestión de Mesas y Reservas**
- Visualización de las mesas disponibles y ocupadas de cada sala.
- Opciones dinámicas para **reservar** y **liberar** mesas.
- Interacciones con botones que permiten realizar acciones dinámicamente usando JavaScript y PHP.

### 2. **Gestión de Recursos (CRUD)**
#### Recursos Administrables:
- **Mobiliario:** Mesas, sillas y salas.
- **Asignación de Imágenes:** Permite a los administradores asociar o cambiar imágenes para cada sala.

#### Funcionalidades CRUD:
- **Crear:** Añadir nuevos recursos.
- **Leer:** Visualizar la lista de recursos existentes.
- **Actualizar:** Modificar las características de los recursos existentes.
- **Eliminar:** Borrar recursos que ya no sean necesarios.

### 3. **Gestión de Usuarios (CRUD)**
#### Usuarios Administrables:
- Camareros
- Administrador
Realmente podremos crear más roles dentro de el

#### Funcionalidades CRUD:
- **Crear:** Añadir nuevos usuarios con sus roles específicos.
- **Leer:** Visualizar la lista de usuarios existentes.
- **Actualizar:** Editar información de usuarios.
- **Eliminar:** Eliminar usuarios.

### 4. **Reservas de Recursos Humanos (Camareros)**
- Reservar camareros en días y franjas horarias específicos.
- Validación de conflictos de horario para evitar solapamientos.

---

## Detalles Técnicos

### **Módulos y Competencias Cubiertas**

#### **M2 - BD**
- Base de datos ampliada para incluir:
  - Gestión de usuarios, roles y permisos.
  - Gestión de recursos (mesas, sillas, salas).
  - Registro de reservas con fechas y franjas horarias.

#### **M6 - DWEC**
- Uso de **JavaScript** para acciones dinámicas:
  - Ventanas modales personalizadas.
  - Validación de formularios.
  - SweetAlerts para alertas interactivas y visualmente atractivas.

#### **M7 - DWES**
- Uso de **PHP PDO** para:
  - Conexión segura a la base de datos.
  - Ejecución de consultas SQL preparadas para evitar inyecciones SQL.
- Gestión de sesiones para usuarios autenticados.
- Implementación de lógica para CRUDs.

#### **M8 - DAW**
- Proyecto alojado en un repositorio de GitHub:
  - Sincronización diaria con el repositorio local.
  - Documentación incluida en este archivo `README.md`.

#### **M9 - DI**
- Diseño homogéneo y profesional:
  - Uso de **Bootstrap 5** para crear interfaces responsivas.
  - Incorporación de iconos personalizados para facilitar la navegación.
  - Distinción visual clara entre las secciones de producción y administración.

---

## Estructura del Proyecto

```plaintext

|-- /view                
|-- /css                 
|-- /img                 
|-- /js                  
|-- /php                
|-- /sql                
|-- README.md
```

---
