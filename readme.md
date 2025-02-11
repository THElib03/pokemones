# Proyecto

## 📂 DATABASE

### Pokedex

- **Name(String)**
- **Type(array)**
- **Image(String)**
- **EvolutionLevel(Int)**
- **Evolution(Pokedex)**

### Pokemon

- **Pokemon(Pokedex)**
- **User(User)**
- **isAlive(Boolean)**
- **Level(Int)**
- **Power(Int)**

### User

- **Role**

### Battle

- **pokemon(Pokemon)**
- **wild(Pokedex)**
- **wildLevel**
- **wildPower**
- **result**

### Relaciones

- **Pokedex** está relacionado con **User** como **N:M** (muchos a muchos).
  - La tabla intermedia **Pokemon** además contiene el **nivel** y **fuerza** del pokemon   concreto.
- **Pokemon** contiene una serie de **Batallas** (**1:N**).
- **Batalla** se relaciona con un **Pokemon** (**N:1**) y un **Pokedex** (**1:N**).

---

## 🎮 Acciones

### 🏹 Cazar

- El pokemon **huye** o **intenta** capturarlo, con un **60%** de probabilidad de éxito.
- **Éxito**: Se añade el pokemon a la colección del usuario.

### 💪 Entrenar

- Aumenta la **fuerza** de un pokemon en **10** puntos.

### ⚔️ Enfrentar

- Batalla contra un pokemon **aleatorio**, comparando **fuerza** y **nivel**.
  - **Si gana**: Sube **1 nivel**.
  - **Si pierde**: **Muere**.

### Evolucionar
    - 

---

## 📺 Vistas

- **Principal**
- **Cazar**
- **Colección**
- **Batalla**
- **Historial Pokémon**


### Funciones

- Train
- GenerateWildPokemon
    - Fight
        - RunAway
        - TryCatch
            - Catched 
            - Evolve
    - Hunt
        - HuntWin
            - Evolve
        - HuntLose