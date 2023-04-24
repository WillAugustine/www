#
# Name: Brandon Mitchell
# Description:  Renames all image files in the "blockRecords" folder.  Doesn't
#               do anything if already ran.  Removes extraneous characters from
#               the titles to simply things for the database and other 
#               operations.  Should be ran before the "sqlInsertBlockRecrdsGen.py"
#               file.
#

import os

path = './blockRecords/'
fileNames = os.listdir(path)

newFileNames = []
for fileName in fileNames:
    
    fileName = fileName.replace("Block ", '')
    fileName = fileName.replace('-', '')
    
    newFileNames.append(fileName)
        
for i in range(len(fileNames)):
    os.rename(path + fileNames[i], path + newFileNames[i])