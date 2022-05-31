#! /usr/bin/env

# absolute path to image folder
FOLDER="app"

# max width
WIDTH=640

# max height
HEIGHT=480

#resize png or jpg to either height or width, keeps proportions using imagemagick
#find ${FOLDER} -iname '*.jpg' -o -iname '*.png' -exec convert \{} -verbose -resize $WIDTHx$HEIGHT\> \{} \;

#resize png to either height or width, keeps proportions using imagemagick
#find ${FOLDER} -iname '*.png' -exec convert \{} -verbose -resize $WIDTHx$HEIGHT\> \{} \;

#resize jpg only to either height or width, keeps proportions using imagemagick
find ${FOLDER} -iname '*.jpg' -exec convert \{} -verbose -resize $WIDTHx$HEIGHT\> \{} \;
find ${FOLDER} -iname '*.JPG' -exec convert \{} -verbose -resize $WIDTHx$HEIGHT\> \{} \;
find ${FOLDER} -iname '*.jpeg' -exec convert \{} -verbose -resize $WIDTHx$HEIGHT\> \{} \;
find ${FOLDER} -iname '*.JPEG' -exec convert \{} -verbose -resize $WIDTHx$HEIGHT\> \{} \;

# find ${FOLDER} -iname '.*.jpg' -exec rm --force \{} \;
# find ${FOLDER} -iname '.*.JPG' -exec rm --force \{} \;
# find ${FOLDER} -iname '.*.jpeg' -exec rm --force \{} \;
# find ${FOLDER} -iname '.*.JPEG' -exec rm --force \{} \;

# alternative
#mogrify -path ${FOLDER} -resize ${WIDTH}x${HEIGHT}% *.png -verbose
