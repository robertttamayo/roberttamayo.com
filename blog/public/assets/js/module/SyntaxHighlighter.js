var SyntaxHighlighter = (function() {
    let reserved = [
        "abstract",
        "arguments",
        "await",
        "boolean",
        "break",
        "byte",
        "case",
        "catch",
        "char",
        "class",
        "const",
        "continue",
        "debugger",
        "default",
        "delete",
        "do",
        "double",
        "else",
        "enum",
        "eval",
        "export",
        "extends",
        "false",
        "final",
        "finally",
        "float",
        "for",
        "function",
        "goto",
        "if",
        "implements",
        "import",
        "in",
        "instanceof",
        "int",
        "interface",
        "let",
        "long",
        "native",
        "new",
        "null",
        "package",
        "private",
        "protected",
        "public",
        "return",
        "short",
        "static",
        "super",
        "switch",
        "synchronized",
        "this",
        "throw",
        "throws",
        "transient",
        "true",
        "try",
        "typeof",
        "var",
        "void",
        "volatile",
        "while",
        "with",
        "yield",
    ];
    
    function format(text) {
        let html = text;
        // console.log(html, text);
        let regex = {
            double: /"/,
            single: /'/,
            backtick: /`/,
            punctuation: /(\.|,|:|\{|\}|\[|\]|;|\+|=|-|\(|\)|\/)/,
            tags: /(<|>)/,
            reserved: /()/,
            whitespace: /\s/,
            nonwhitespace: /\S/,
            letter: /[A-Za-z]/,
            newline: /\n/,
            tab: /\t/,
            openTag: /</,
            closeTag: />/,
            slash: /\//,
            asterisk: /\*/,
            inlineComment: /\/\//,
            commentOpen: /\/\*/,
            commentClose: /\*\//,
        }
        let output = '';
        let working = '';

        let inQuotes = false;
        let quoteType = regex.double;

        let inTag = false;
        let htmlTag = null;
        
        let hasSlash = false;
        let inComment = false;
        let commentType = null;
        let hasName = false;

        html = html.toString();
        for (let i = 0; i < html.length; i++) {
            let char = html.charAt(i);
            let outputChar = '&#' + char.charCodeAt(0);
            if (inQuotes) {
                if (quoteType.test(char)) {
                    output += outputChar + '</span>';
                    inQuotes = false;
                } else {
                    if (regex.whitespace.test(char)) {
                        writeWhiteSpace(char);
                    } else {
                        output += outputChar;
                    }
                }
            } else if (inComment) {
                if (commentType == 'inline') {
                    if (regex.newline.test(char)) {
                        inComment = false;
                        commentType = '';
                        output += '</span>';
                        writeWhiteSpace(char);
                    } else {
                        if (regex.whitespace.test(char)) {
                            writeWhiteSpace(char);
                        } else {
                            output += outputChar;
                        }
                    }
                } else {
                    if (regex.asterisk.test(char)) {
                        let nextIndex = i + 1;
                        if (nextIndex < html.length) {
                            let nextChar = html.charAt(nextIndex);
                            if (regex.slash.test(nextChar)) {
                                inComment = false;
                                commentType = '';
                                output += '*/</span>';
                                i = nextIndex;
                                continue;
                            }
                        }
                    } else {
                        if (regex.whitespace.test(char)) {
                            writeWhiteSpace(char);
                        } else {
                            output += outputChar;
                        }
                    }
                }
            } else {
                if (!regex.whitespace.test(char)) {
                    if (regex.letter.test(char)) {
                        working += char;
                        continue;
                    } else if (regex.slash.test(char)) {
                        // lookahead
                        let nextIndex = i + 1;
                        if (nextIndex < html.length) {
                            let nextChar = html.charAt(nextIndex);
                            if (regex.asterisk.test(nextChar)){
                                inComment = true;
                                commentType = 'block';
                                output += '<span class="comm">/*';
                                i = nextIndex;
                                continue;
                            } else if (regex.slash.test(nextChar)) {
                                inComment = true;
                                commentType = 'inline';
                                output += '<span class="comm">//';
                                i = nextIndex;
                                continue;
                            }
                        }
                    } else if (inTag && regex.closeTag.test(char)) {
                        workingTest(true);
                        inTag = false;
                        // output += `<span class="punc">${outputChar}</span>`;
                    } else if (regex.openTag.test(char)) {
                        let nextIndex = i + 1;
                        if (nextIndex < html.length) {
                            let nextChar = html.charAt(nextIndex);
                            if (!regex.whitespace.test(nextChar)){
                                workingTest(true);
                                inTag = true;
                                hasName = false;
                            } else {
                                workingTest();
                            }
                        } else {
                            workingTest();
                        }
                        // output += `<span class="punc">${outputChar}</span>`;
                    } else {
                        workingTest();
                    }

                    if (regex.punctuation.test(char)) {
                        output += `<span class="punc">${outputChar}</span>`;
                    } else if (regex.tags.test(char)) {
                        output += `<span class="tags">${outputChar}</span>`;
                    } else if (regex.double.test(char)) {
                        inQuotes = true;
                        quoteType = regex.double;
                        output += `<span class="quot">${outputChar}`;
                    } else if (regex.single.test(char)) {
                        inQuotes = true;
                        quoteType = regex.single;
                        output += `<span class="quot">${outputChar}`;
                    } else if (regex.backtick.test(char)) {
                        inQuotes = true;
                        quoteType = regex.backtick;
                        output += `<span class="quot">${outputChar}`;
                    } else {
                        // console.log('char that is not any match: ' + char);
                        output += outputChar;
                    }
                } else {
                    workingTest();
                    writeWhiteSpace(char);
                }
            }
        }
        // clean up
        if (inComment) {
            output += '</span>';
        }
        if (inQuotes) {
            output += '</span>';
        }

        // console.log(output);
        function writeWhiteSpace(char){
            if (regex.newline.test(char)) {
                output += '<br>';
            } else if (regex.tab.test(char)) {
                output += '&nbsp;&nbsp;&nbsp;&nbsp;';
            } else {
                output += '&nbsp;';
            }
        }
        function workingTest(log){
            if (log) {
                // console.log('testing working: ' + working);
            }
            if (!inTag && reserved.indexOf(working) != -1) {
                output += `<span class="lang">${working}</span>`;
            } else if (inTag && !hasName) {
                output += `<span class="name">${working}</span>`;
                hasName = true;
            } else {
                output += working;
            }
            working = '';
        }
        return output;
    }
    
    return {
        getFormattedHTML: function(element){
            return format(element);
        }
    }
}());

$(document).ready(function(){
    $('pre').each((index, el) => {
        let $formatted = $('<pre class="formatted"></pre>');
        let text = $(el).html();
        text = text.replace(/&nbsp;/g, ' ');
        text = text.replace(/<br>/g, '\n');
        text = text.replace(/&gt;/g, '>');
        text = text.replace(/&lt;/g, '<');
        let formattedHTML = SyntaxHighlighter.getFormattedHTML(text);
        $formatted.html(formattedHTML);
        $(el).replaceWith($formatted);
    });
});
