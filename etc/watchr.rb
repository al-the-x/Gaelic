watch(/(lib|app)\/.+\.php$/) do |matches|
    changed = matches[0]

    testfile = changed

    testfile.sub!(/\.php/, 'Test.php') unless testfile.match(/Test\.php/)

    testfile.sub('Gaelic', 'Test')

    system "clear && echo $(date): #{changed} && phpunit --verbose --configuration etc/phpunit.xml #{testfile}"
end
