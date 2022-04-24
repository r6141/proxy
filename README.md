A project to calculate days you can bunk.

database is made using sqlite3, backend in php, and frontend probably in js or some js framework.

# goals

1. ~~add a section(wiki?) to explain how the data is stored in the db and how to access it~~
2. make 2 formats to view 
   - monthly view = (shows all months from 1-12)
     - has same tile as the days in the month
     - tile can be gree or red or blue, caluated if present, absent or not defined. (! not defined tiles are not taken into calculation for other views)
  
   - year view = (shows all years)
     - has 12 tile, each of which shows overall stat for that month
     - tile can be green or red, calculated from percentage of days present.
  
