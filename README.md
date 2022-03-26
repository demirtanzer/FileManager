# FileManager
With this File Manager prepared for PHP/Js, you can perform all file operations on your server without any problems.

<article id="cont" class="active">
    <p>
        <br />
        <font style="vertical-align: inherit;">
            <font style="vertical-align: inherit;">
                Instead of downloading and integrating the file manager I need in the projects I prepared, I decided to create both OOP experience and my own PHP FileManager class and a visual interface that I can use in this class.
            </font>
        </font>
        <br />
        <br />
        <font style="vertical-align: inherit;">
            <font style="vertical-align: inherit;">Contrary to the standards, I do the version notification using the following method so that it is clear what content I am using during a possible change in the future.</font>
        </font>
        <br />
        <br />
    </p>
    <br />
    <h4>
        <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Version features;</font></font>
    </h4>
    <br />
    <ul>
        <li>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">In FileManager.php v2 I used the FMP namespace and the fmp class name to further reduce the code complexity found in v1.</font></font>
        </li>
        <li>
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">Unlike the previous version, in this version, graphic files are previewed with the "tempImage()" function in the class instead of standard icons.</font>
            </font>
        </li>
        <li>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Quick access thanks to the folder tree, which can be used on a screen with a resolution of 640px and above for ease of use.</font></font>
        </li>
        <li>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Popup menus with customized jQuery-ui plugin</font></font>
        </li>
        <li>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Multi-language support with xml format</font></font>
        </li>
        <li>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">Built-in FontAwesome 4 icons</font></font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">The interface will not work without jQuery and console.log() will create a record</font></font>
            </b>
        </li>
        <li>
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">With the "config.xml" file, the root directory, color scheme and maximum file upload size can be specified.</font></font>
        </li>
    </ul>
    <br />
    <h4>
        <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">FMP class in FileManager.php;</font></font>
    </h4>
    <br />
    <ol>
        <li>
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">First of all, I had to redefine functions such as mime_content_type() and realpath() in the class in order not to cause software errors between possible PHP versions.</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;post($_POST variable)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;"> To be able to use the posted data securely. </font><font style="vertical-align: inherit;">Returns "false" if the value is undefined/empty, otherwise returns the posted data.</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;tempImage(imageLocation,desiredType,tempFolder)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;"> If there is no image sent in the specified tempFolder, it will be reduced to 45px*45px and saved in the tempFolder with a new name in md5() format. </font>
                <font style="vertical-align: inherit;">Returns null if imageLocation is not found, otherwise returns with image content</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp->details(adress)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;">type(text file), graphic(true/false), server location(/home/dir/), web location(/dir), size( Returns 1mb), edit date(Ynd H:i:sa), and privacy(777)</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;create(name,path,type)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;"> Creates the desired name and type (file/folder) at the specified path. </font>
                <font style="vertical-align: inherit;">Adds a number to the end of the filename if there is content with the same name in the specified path. </font>
                <font style="vertical-align: inherit;">If there is no error, it returns the name of the created content.</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;fileSave(file,content)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Replaces the contents of the file at the specified address. </font><font style="vertical-align: inherit;">returns true/false</font></font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;listing(folder,requestType</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> ) Returns the contents of the specified folder in the desiredType (all, folders only, only files) as an array variable.</font></font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;folderList(folder)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;"> On the contrary, fmp-&gt;listing() function returns only the folders in the specified folder in string format with comma marks (,) between them.</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;download(file)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> It provides a secure download of the desired file by making the necessary header() definitions.</font></font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;delete(files)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;"> Deletes the file/s sent in both array and string form. </font><font style="vertical-align: inherit;">Returns deleted and non-deletable filenames.</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;move(files,movingFolder) Moves files,</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> both array and string, to the specified moveFolder. Returns True/False.</font></font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;copy(files,copyFolder) Copies</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> both array and string files to the specified copyFolder. Returns True/False.</font></font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;rename(oldName,newName)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> renames oldName content with newName.</font></font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;upload(files, uploadFolder) Uploads</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;"> files sent either as array or string to the upload folder and names them according to the file existence. </font>
                <font style="vertical-align: inherit;">Returns with array elements named Error and done</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;createZip(files,creationFolder)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;">
                <font style="vertical-align: inherit;"> Creates a zip archive with both array and string files, in the specified creationFolder. It returns array elements named Error and done.</font>
            </font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">fmp-&gt;unZip(archiveFile,folder)</font></font>
            </b>
            <br />
            <font style="vertical-align: inherit;"><font style="vertical-align: inherit;"> Extracts the specified archiveFile in the desired directory. Returns True/False.</font></font>
        </li>
        <li>
            <b>
                <font style="vertical-align: inherit;">
                    <font style="vertical-align: inherit;">In case of a possible error in all operations </font><font style="vertical-align: inherit;">, the error date, operation and necessary information are recorded in the </font>
                </font>
                <b>
                    <font style="vertical-align: inherit;"><font style="vertical-align: inherit;">"/manager/fm_error_log.txt" file.</font></font>
                </b>
                <font style="vertical-align: inherit;"></font>
            </b>
        </li>
    </ol>
    <p></p>
</article>
