;; how to use this helper?
;; 1. run "git log --reverse 2021-04-25..master > tmp.md" (2021-04-25 is example tag of previous version)
;; 2. copy contents of template.md to the start of tmp.md
;; 3. in emacs M-x load-file then choose helper.el

(defun rssbridge-log--get-commit-block()
  (interactive)
  (search-backward "commit ") ;;  (move-beginning-of-line 1)
  (set-mark-command nil)
  (right-char)
  (search-forward "commit ") ;; (move-end-of-line 1)
  )

(defun rssbridge-log--remove-until-commit-block-start()
  (interactive)
  (move-beginning-of-line 1)
  (set-mark-command nil)
  (search-backward "commit ")
  (delete-region (region-beginning) (region-end))
  )

(defun rssbridge-log--cut-paste(arg)
  (interactive)
  (kill-whole-line 0)
  (goto-line 0)
  (search-forward arg)
  (move-end-of-line 1)
  (newline)
  (yank)
  (set-mark-command 1)
  (search-forward "commit ")
  )

(defun rssbridge-log-copy-as-new()
  (interactive)
  (rssbridge-log--get-commit-block)
  (replace-regexp ".*\\[\\(.*\\)\\].*\\((.*)\\)" "* \\1 () \\2" nil (region-beginning) (region-end))
  (rssbridge-log--remove-until-commit-block-start)
  (rssbridge-log--cut-paste "## New bridges")
  )

(defun rssbridge-log-copy-as-mod()
  (interactive)
  (rssbridge-log--get-commit-block)
  (replace-regexp ".*\\[\\(.*\\)\\]" "* \\1:" nil (region-beginning) (region-end))
  (rssbridge-log--remove-until-commit-block-start)
  (rssbridge-log--cut-paste "## Modified bridges")
  )

(defun rssbridge-log-remove()
  (interactive)
  (rssbridge-log--get-commit-block)
  (rssbridge-log--remove-until-commit-block-start)
  (set-mark-command 1)
  (search-forward "commit ")
  )

(defun rssbridge-log-copy-as-gen()
  (interactive)
  (rssbridge-log--get-commit-block)
  (replace-regexp ".*\\[\\(.*\\)\\]" "* \\1:" nil (region-beginning) (region-end))
  (rssbridge-log--remove-until-commit-block-start)
  (rssbridge-log--cut-paste "## General")
  )
