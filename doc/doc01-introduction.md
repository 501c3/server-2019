#Georgia DanceSport Competition Registration Project
In a DanceSport competition, the entities and their relationships are as follows:

1. A **Person** is described by type (Professional or Amateur), status (Teacher or Student), 
sex (Male or Female), age in years and proficiency (Bronze, Silver etc).  If they 
become part of a team then they are given a designation such as Partner 'A' or Partner 'B'.
2. The person then becomes part of a **Team**.  The **Team** may consist of a one or more **Person**. 
The **Team** is subsequently described by type (Professional-Amateur, Amateur-Amateur etc) and
a status (Teacher-Student, Student-Student), a combination of sexes (Male-Female, Male-Male, Female-Female),
The **Team** is subsequently given a proficiency (Bronze, Silver etc) and a team age.  
3. Each **Team** may enter an **Event** contingent upon the age, proficiency, type, status and sex
of the team.


Typically for most Student-Student teams where both competitors are adults, the team proficiency 
is determined by the proficiency of person with the highest proficiency.  The age of the team is 
determined by the age of the youngest person.  There are exceptions to these rules.  For
youth the age of the team is determined by the oldest **Person**.    

These rules and their exceptions are encoded into a MySQL database via Yaml files which 
explicitly specifies which persons may be part of a **Team Class**.  

There are table lookups for **Person** to **Team Class** classifications.  Subsequently
a yaml file is used to define all possible events.  Finally an Event to Team realtionship file
is defined which specifies which **Team Class** can enter a specific event.



####Table of Contants

* [Overview](./doc02-overview.md)
* [Primitives:Models,Domains,Values](./doc03-primitives.md)
* [Person Entity Generation File](./doc04-person.md)
* [Team Entity Generation File](./doc05-team.md)
* [Event Entity Generation File](./doc06-event.md)
* [Event-Team Relationship File](./doc07-event-team.md)
