# WebsiteMapper.py
# Prints a tree graph of any website.
import urllib2
from os.path import basename
import urlparse
from BeautifulSoup import BeautifulSoup # for HTML parsing

global urlList
urlList = []

def printWebsiteMap(url, level = 0):

    # do not go to other websites
    global website
    netloc = urlparse.urlsplit(url).netloc
    netlocSplit = netloc.split('.')
    if netlocSplit[-2] + netlocSplit[-1] != website:
        return

    global urlList
    if url in urlList: # prevent using the same URL again
        return

    try:
        urlContent = urllib2.urlopen(url).read()
        soup = BeautifulSoup(''.join(urlContent))
        urlList.append(url)
    except:
        return

    # if not an HTML file then return
    if urlContent.find('<html') == -1 and urlContent.find('<HTML') == -1:
        return

    if level == 0:
        print url
    else:
        print '  ' * (level - 1) + '|'
        print '  ' * (level - 1) + '|' +'__' * level + url

    global maxLevel
    if level < maxLevel:        
        # if there are links on the webpage then recursively repeat
        linkTags = soup.findAll('a')

        for linkTag in linkTags:
            try:
                linkUrl = linkTag['href']

                # skip if URL is a section on the same webpage
                if linkUrl.startswith('#'):
                    return

                # if relative URL then convert to absolute
                if urlparse.urlsplit(linkUrl).scheme == '':
                    linkUrl = urlparse.urlsplit(url).scheme + '://' + netloc + '/' + linkUrl

                # remove '/' in the end if exists
                if linkUrl.endswith('/'):
                    linkUrl = linkUrl.strip('/')

                printWebsiteMap(linkUrl, level + 1)
            except:
                pass

# MAIN
rootUrl = 'http://www.bloodshed.net'
netloc = urlparse.urlsplit(rootUrl).netloc.split('.')
global website
website = netloc[-2] + netloc[-1]
global maxLevel
maxLevel = 4
printWebsiteMap(rootUrl)