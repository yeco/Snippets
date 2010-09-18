# Hardlink identical files in current directory (on unix, this means they have share physical storage, meaning much less space)
import os
import hashlib

dupes = {}

for path, dirs, files in os.walk(os.getcwd()):
    for file in files:
        filename = os.path.join(path, file)
        hash = hashlib.sha1(open(filename).read()).hexdigest()
        if hash in dupes:
            print 'linking "%s" -> "%s"' % (dupes[hash], filename)
            os.rename(filename, filename + '.bak')
            try:
                os.link(dupes[hash], filename)
                os.unlink(filename + '.bak')
            except:
                os.rename(filename + '.bak', filename)
            finally:
        else:
            dupes[hash] = filename