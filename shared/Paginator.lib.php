<?php


class Paginator {

	private $limit;		// Number of Items per page
	private $totalNumberOfItems;
	private $baseURL;
	private $numberOfPages;
	private $currentPage;
	private $nextPage;
	private $previousPage;
	private $lastPage;
	private $numberOfAdjacentPages;
	
	private $words = array(
						'Next' 		=> 'التالي',
						'Previous' 	=> 'السابق',
						'First'		=> 'الأول',
						'Last'		=> 'الآخر',
					);

	public function __construct($currentPage = 1, $totalNumberOfItems, $limit = 10, $baseURL, $numberOfAdjacentPages = 5) {
		$this->totalNumberOfItems 	 = $totalNumberOfItems;
		$this->limit 				 = $limit;
		$this->baseURL 				 = $baseURL;
		$this->numberOfAdjacentPages = $numberOfAdjacentPages;

		if ($currentPage) {
			$this->currentPage = intval($currentPage);
		} else {
			$this->currentPage = 1;
		}

		$this->lastPage = ceil($totalNumberOfItems/$limit);

		if ($currentPage < 1 or $currentPage > $this->lastPage) {
			$this->currentPage = 1;
		}
		// prepare some values
		$this->previousPage = $this->currentPage - 1;
		$this->nextPage 	= $this->currentPage + 1;

	}

	public function doPaginate($className = "paginate") {
		// if we have one page, do nothing
		if ($this->totalNumberOfItems <= $this->limit) {
			return "";
		}

		$html = "<div class='" . $className . "'>";

		// prev
		if ($this->currentPage > 1) {
			$html .= sprintf( "<a href='%s1'>« %s</a> ", $this->baseURL, $this->words['First']);
			$html .= sprintf("<a href='%s'>« %s</a>", $this->baseURL . $this->previousPage, $this->words['Previous']);
		} else {
		//	$html .= sprintf(" <span class='page'>« %s</span> ", $this->words['First']);
		//	$html .= sprintf("<span class='page'>« %s</span>", $this->words['Previous']);
		}

		//loop
		// set pages before current

		if ($this->currentPage <= $this->numberOfAdjacentPages +1) {
			for ($i = 1; $i < $this->currentPage; $i++) {
				$html .= sprintf(" <a href='%s'>%s</a> ", $this->baseURL . $i, $i);
			}
		} else {
			$html .= "...";
			$n = $this->currentPage - $this->numberOfAdjacentPages;
			for ($i = $n; $i < $this->currentPage; $i++) {
				$html .= sprintf(" <a href='%s'>%s</a> ", $this->baseURL . $i, $i);
			}
		}

		// set current page
		$html .= sprintf(" <span class='page current-page'>%s</span> ", $this->currentPage);

		// set pages after current
		$pagesAfterCurrent = $this->lastPage - $this->currentPage;

		if ($pagesAfterCurrent <= $this->numberOfAdjacentPages) {
			for ($i = $this->currentPage + 1; $i <= $this->lastPage; $i++) {
				$html .= sprintf(" <a href='%s'>%s</a> ", $this->baseURL . $i, $i);
			}
		} else {
			$n = $this->currentPage + $this->numberOfAdjacentPages;
			for ($i = $this->currentPage + 1; $i <= $n; $i++) {
				$html .= sprintf(" <a href='%s'>%s</a> ", $this->baseURL . $i, $i);
			}
			$html .= " ... ";
		}

		//next
		if ($this->currentPage < $this->lastPage) {			
			$html .= sprintf("<a href='%s'>%s »</a>", $this->baseURL . $this->nextPage, $this->words['Next']);
			$html .= sprintf(" <a href='%s'>%s »</a> ", $this->baseURL . $this->lastPage, $this->words['Last']);
		} else {
		//	$html .= sprintf("<span class='page'>%s »</span>", $this->words['Next']);
		//	$html .= sprintf(" <span class='page'>%s »</span> ", $this->words['Last']);
		}

		$html .= "</div>";
		return $html;

	}
}
?>
