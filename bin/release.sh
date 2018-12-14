#!/usr/bin/env bash

if [ $# -ne 1 ]; then
	echo "Usage: $0 <version>"
	exit
fi

VERSION=$1

DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
SRC="${DIR}/src"
LOCAL="${DIR}/wp-plugin-repo"
REPO="https://plugins.svn.wordpress.org/mailmojo-widget"

echo "Releasing version ${VERSION}..."

if [ ! -d "$LOCAL" ]; then
	echo "Checking out plugin repo from WordPress SVN..."
	svn co "$REPO" "$LOCAL"
else
	echo "Making sure SVN plugin repo is up to date..."
	cd "$LOCAL"
	svn up
fi

echo "Copying changes from src..."
cd $DIR
cp "$SRC"/*.php "${LOCAL}/trunk"
cp "$SRC"/*.txt "${LOCAL}/trunk"
cp -r "${SRC}/css" "${LOCAL}/trunk"
cp -r "${SRC}/languages" "${LOCAL}/trunk"
cp -r "${SRC}/templates" "${LOCAL}/trunk"
cp -r "${SRC}/vendor" "${LOCAL}/trunk"

echo "Commiting changeset for version ${VERSION}..."
cd $LOCAL
svn ci -m "Adding changeset for version ${VERSION}"

echo "Tagging release version ${VERSION}..."
svn cp trunk "tags/${VERSION}"
svn ci -m "Tagging version ${VERSION}"

echo "Version ${VERSION} released!"
