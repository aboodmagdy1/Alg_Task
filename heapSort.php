<?php


function heapify(&$arr, $n, $i)
{
    $largest = $i; // Initialize largest as root
    $left = 2 * $i + 1; // Left child index
    $right = 2 * $i + 2; // Right child index

    // Check if left child exists and is greater than root
    if ($left < $n && $arr[$left] > $arr[$largest]) {
        $largest = $left;
    }

    // Check if right child exists and is greater than largest so far
    if ($right < $n && $arr[$right] > $arr[$largest]) {
        $largest = $right;
    }

    // If largest is not root, swap and continue heapifying
    if ($largest != $i) {
        [$arr[$i], $arr[$largest]] = [$arr[$largest], $arr[$i]]; // Swap
        heapify($arr, $n, $largest);
    }
}

function heapSort(&$arr)
{
    $n = count($arr);

    // Build max heap
    for ($i = intdiv($n, 2) - 1; $i >= 0; $i--) {
        heapify($arr, $n, $i);
    }

    // Extract elements from heap one by one
    for ($i = $n - 1; $i > 0; $i--) {
        [$arr[0], $arr[$i]] = [$arr[$i], $arr[0]]; // Swap root with the last element
        heapify($arr, $i, 0); // Heapify reduced heap
    }
}

// Example usage
$arr = [12, 11, 13, 5, 6, 7];
heapSort($arr);
echo "Sorted array: " . implode(", ", $arr);
