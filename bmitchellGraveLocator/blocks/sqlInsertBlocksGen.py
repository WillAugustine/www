#
# Name: Brandon Mitchell
# Description:  Reads in the "Block Centers.csv" and "Block Corners.csv" data
#               and constructs an SQL file from them.
#

# Several blocks are missing, simpler to use a dictionary with blocks as keys
blocks = dict()

with open("./blockData/Block Centers.csv") as file:

    # Skip headers in first line
    blockCenters = file.readlines()[1:]
    
with open("./blockData/Block Corners.csv") as file:
    blockCorners = file.readlines()[1:]
    
for line in blockCenters:
    block, centerLat, centerLong = [word.strip() for word in line.split(',')]
    blocks[block] = {"center": (centerLat, centerLong)}
    
for line in blockCorners:
    block, direction, lat, long = [word.strip() for word in line.split(',')]
    blocks[block][direction] = (lat, long)
    
outputStr = """/*
 * Name:    Brandon Mitchell
 * Description: Inserts all the block data into the block table in the 
 *              bmitchellCemeteryProject database.
 */
 
USE bmitchellCemeteryProject;

INSERT INTO blocks (blockID, SELat, SELong, SWLat, SWLong, NELat, NELong, NWLat, NWLong, centerLat, centerLong) VALUES
"""

inserts = []
for key, value in blocks.items():
    item = f"({key}, "
    item += f"{value['SE'][0]}, {value['SE'][1]}, "
    item += f"{value['SW'][0]}, {value['SW'][1]}, "
    item += f"{value['NE'][0]}, {value['NE'][1]}, "
    item += f"{value['NW'][0]}, {value['NW'][1]}, "
    item += f"{value['center'][0]}, {value['center'][1]})"
    
    inserts.append(item)

# Use join as it won't put the comma on the last line
outputStr += ",\n".join(inserts) + ';'

with open("insertBlocks.sql", 'w') as file:
    file.write(outputStr)