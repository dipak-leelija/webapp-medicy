<?php

Class Pagination extends DatabaseConnection{

    function productsWithPagination() {
        // Number of records per page
        $recordsPerPage = 16;
    
        // Get the current page number from the URL, default to 1
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
    
        // Calculate the starting record for the current page
        $startFrom = ($page - 1) * $recordsPerPage;
    
        // Query to retrieve records for the current page
        $sql = "SELECT * FROM products LIMIT $startFrom, $recordsPerPage";
        $result = $this->conn->query($sql);
    
        // Fetch the records
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    
        // Pagination links
        $sql = "SELECT COUNT(*) AS total FROM products";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $totalRecords = $row['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage);


        $paginationHTML = "<ul class='pagination'>";
    
        // Previous button
        if ($page > 1) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>First</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=".($page-1)."'>Previous</a> </li>";
        }
    
        // Display 7 pages initially
        if ($totalPages <= 7) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$i' ";
                if ($i == $page) {
                    $paginationHTML .= "class='current' disabled";
                }
                $paginationHTML .= ">$i</a> </li>";
            }
        } else {
            // When there are more than 7 pages
            $startPage = max(1, $page - 3);
            $endPage = min($totalPages, $page + 3);
    
            // Show first page, middle page, and middle of the middle page
            if ($page - 3 > 1) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>1</a> </li>";
                // $paginationHTML .= "<span>...</span> ";
            }
    
            for ($i = $startPage; $i <= $endPage; $i++) {
                $paginationHTML .= "<li class='page-item'><a href='?page=$i' ";
                $paginationHTML .= $i == $page ? "class='page-link shadow-none disabled'" : "class='page-link shadow-none'";
                // }
                $paginationHTML .= ">$i</a> </li>";
            }
    
            // Show last page, middle page of last, and next page
            if ($page + 3 < $totalPages) {
                // $paginationHTML .= "<span>...</span> ";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>$totalPages</a> </li>";
            }
        }
    
        // Next button
        if ($page < $totalPages) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=".($page+1)."'>Next</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>Last</a> </li>";
        }
    
        $paginationHTML .= "</ul>";
    
        // Close the database connection
        $this->conn->close();
    
        // Return the data and pagination HTML
        return ['totalPtoducts' => $totalRecords, 'products' => $products, 'paginationHTML' => $paginationHTML];
    }
    



    function dataPagination($recordsPerPage, $table, $columnId = 'id') {
    
        // Get the current page number from the URL, default to 1
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
    
        // Calculate the starting record for the current page
        $startFrom = ($page - 1) * $recordsPerPage;
    
        // Query to retrieve records for the current page
        $sql = "SELECT * FROM $table LIMIT $startFrom, $recordsPerPage ORDER BY $columnId DESC";
        $result = $this->conn->query($sql);
    
        // Fetch the records
        $products = [];
        while ($row = $result->fetch_assoc()) {
            $products[] = $row;
        }
    
        // Pagination links
        $sql = "SELECT COUNT(*) AS total FROM $table";
        $result = $this->conn->query($sql);
        $row = $result->fetch_assoc();
        $totalRecords = $row['total'];
        $totalPages = ceil($totalRecords / $recordsPerPage);


        $paginationHTML = "<ul class='pagination'>";
    
        // Previous button
        if ($page > 1) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>First</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=".($page-1)."'>Previous</a> </li>";
        }
    
        // Display 7 pages initially
        if ($totalPages <= 7) {
            for ($i = 1; $i <= $totalPages; $i++) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$i' ";
                if ($i == $page) {
                    $paginationHTML .= "class='current' disabled";
                }
                $paginationHTML .= ">$i</a> </li>";
            }
        } else {
            // When there are more than 7 pages
            $startPage = max(1, $page - 3);
            $endPage = min($totalPages, $page + 3);
    
            // Show first page, middle page, and middle of the middle page
            if ($page - 3 > 1) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>1</a> </li>";
                // $paginationHTML .= "<span>...</span> ";
            }
    
            for ($i = $startPage; $i <= $endPage; $i++) {
                $paginationHTML .= "<li class='page-item'><a href='?page=$i' ";
                $paginationHTML .= $i == $page ? "class='page-link shadow-none disabled'" : "class='page-link shadow-none'";
                // }
                $paginationHTML .= ">$i</a> </li>";
            }
    
            // Show last page, middle page of last, and next page
            if ($page + 3 < $totalPages) {
                // $paginationHTML .= "<span>...</span> ";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>$totalPages</a> </li>";
            }
        }
    
        // Next button
        if ($page < $totalPages) {
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=".($page+1)."'>Next</a> </li>";
            $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>Last</a> </li>";
        }
    
        $paginationHTML .= "</ul>";
    
        // Close the database connection
        $this->conn->close();
    
        // Return the data and pagination HTML
        return ['totalPtoducts' => $totalRecords, 'products' => $products, 'paginationHTML' => $paginationHTML];
    }
    



    function arrayPagination($myArr, $recordsPerPage = 16) {

        if ($myArr != null || count($myArr) > 0) {
            // Get the current page number from the URL, default to 1
            $page = isset($_GET['page']) ? $_GET['page'] : 1;
        
            // Calculate the starting record for the current page
            $startFrom = ($page - 1) * $recordsPerPage;
        
            // Get the total number of records
            $totalRecords = count($myArr);
        
            // Calculate the total number of pages
            $totalPages = ceil($totalRecords / $recordsPerPage);
        
            // Get the records for the current page
            $items = array_slice($myArr, $startFrom, $recordsPerPage);
        
            $paginationHTML = "<ul class='pagination'>";
        
            // Previous button
            if ($page > 1) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>First</a></li>";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page - 1) . "'>Previous</a></li>";
            }
        
            // Display 7 pages initially
            if ($totalPages <= 7) {
                for ($i = 1; $i <= $totalPages; $i++) {
                    $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$i'";
                    if ($i == $page) {
                        $paginationHTML .= " class='current' disabled";
                    }
                    $paginationHTML .= ">$i</a></li>";
                }
            } else {
                // When there are more than 7 pages
                $startPage = max(1, $page - 3);
                $endPage = min($totalPages, $page + 3);
        
                // Show first page, middle page, and middle of the middle page
                if ($page - 3 > 1) {
                    $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=1'>1</a></li>";
                }
        
                for ($i = $startPage; $i <= $endPage; $i++) {
                    $paginationHTML .= "<li class='page-item'><a href='?page=$i'";
                    $paginationHTML .= $i == $page ? " class='page-link shadow-none disabled'" : " class='page-link shadow-none'";
                    $paginationHTML .= ">$i</a></li>";
                }
        
                // Show last page, middle page of last, and next page
                if ($page + 3 < $totalPages) {
                    $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>$totalPages</a></li>";
                }
            }
        
            // Next button
            if ($page < $totalPages) {
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=" . ($page + 1) . "'>Next</a></li>";
                $paginationHTML .= "<li class='page-item'><a class='page-link shadow-none' href='?page=$totalPages'>Last</a></li>";
            }
        
            $paginationHTML .= "</ul>";
        
            // Return the data and pagination HTML
            return json_encode(['status'=> 1, 'totalitem' => $totalRecords, 'items' => $items, 'paginationHTML' => $paginationHTML]);
        }else {
            // return json_encode(['status'=> 0, 'totalProducts' => '', 'products' => '', 'paginationHTML' => '']);
            
        }
    }
    
}

?>
