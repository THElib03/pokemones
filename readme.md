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

- Un jugador puede elegir uno de sus pokemones y hacerlo luchar contra:
  - Un pokemon salvaje aleatorio.
  - Un pokemon de otro jugador.
  - Elegir varios pokemones propios y luchar contra un grupo de pokemones de otro jugador.

- La Batalla contra otro pokemon **aleatorio**, se decide comparando **fuerza** multiplicada por **nivel** de cada pokemon.
  En el caso de equipos, se puede ver el equipo enermigo y reordenar para elegir contra quien enfrentarse, se compara cada pareja de la misma manera y quien gane mas combates gana la pelea.
  Al terminar la batalla:
  - **Si gana**: El usuario elige entre subir su pokemon/es **1 nivel**, cazar al pokemon vencido o resucitar un pokemon propio.
  - **Si pierde**: **Muere** y no se puede volver a usar hasta ser resucitado.

### Evolucionar

- Alcanzado cierto **nivel**, un pokemon puede evolucionar si el usuario lo decide.

---

## 📺 Vistas

- **Principal**
- **Cazar**
- ~~**Colección**~~ (Falta implementar las acciones)
- **Batalla**
- **Historial Pokémon**


### Funciones

~~- Train ~~
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