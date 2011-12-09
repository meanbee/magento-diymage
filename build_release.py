#!/usr/bin/python

import os

def addLicense(inputFilePath, outputFilePath):
    headerFile = open("HEADER_LICENSE.txt")
    inputFile = open(inputFilePath)
    outputFile = open("tmp","a") 

    print "Adding license to %s... " % inputFilePath,

    for line in inputFile:
        if "{{license}}" in line:
            outputFile.writelines(headerFile)
            print "[DONE] ",
        else: 
            outputFile.write(line) 
    outputFile.close()

    os.rename("tmp", outputFilePath)
    print


# iterate over all the files and add licenses to them
for root, dirs, files in os.walk(os.getcwd()):
    dirPath = root.replace(os.getcwd() + '', 'release') 
    os.makedirs(dirPath)
    if ".git" not in dirPath:
        for f in files:
            if f != "build_release.py":
                addLicense(os.path.join(root, f), '/'.join([dirPath, f]))
