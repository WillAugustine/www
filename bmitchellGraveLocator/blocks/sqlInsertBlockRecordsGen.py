#
# Name: Brandon Mitchell
# Description:  Reads in the all the files located in the blockRecords folder,
#               extracts the block number, and generates an SQL file that will
#               inser the fileName and block number into the database.  The
#               renameImages.py file must be run before this to adjust the 
#               titles of the files.
#

import os

path = './blockRecords/'
fileNames = os.listdir(path)

# Set to use in verifying that a char is 0-9
digits = {str(x) for x in range(10)}

blockIDs = []
for fileName in fileNames:
    index = 0
    
    # Block ID is first due to rename, extract for insertion code
    while fileName[index] in digits:
        index += 1
        
    blockID = fileName[:index]
    blockIDs.append(blockID)

outputStr = """/*
 * Name:    Brandon Mitchell
 * Description: Inserts all the block record data into the block table in the 
 *              bmitchellCemeteryProject database.  Sets up the foreign keys
 *              into the blocks table so it must be ran after insertBlockData.sql.
 */
 
USE bmitchellCemeteryProject;

INSERT INTO blockRecords (imageFileName, blockID) VALUES
"""

inserts = []
for i in range(len(fileNames)):

    # I remove the file extension for simplicity, don't have to remove later 
    inserts.append(f"(\"{fileNames[i].replace('.JPG', '')}\", {blockIDs[i]})")
    
# Use join as it won't put the comma on the last line
outputStr += ",\n".join(inserts) + ';'

with open("insertBlockRecords.sql", 'w') as file:
    file.write(outputStr)