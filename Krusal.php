<?php
class Edge
{
    public $src, $dest, $weight;

    public function __construct($src, $dest, $weight)
    {
        $this->src = $src;
        $this->dest = $dest;
        $this->weight = $weight;
    }
}

class Graph
{
    public $vertices, $edges;

    public function __construct($vertices)
    {
        $this->vertices = $vertices;
        $this->edges = [];
    }

    public function addEdge($src, $dest, $weight)
    {
        $this->edges[] = new Edge($src, $dest, $weight);
    }

    private function find($parent, $vertex)
    {
        if ($parent[$vertex] != $vertex) {
            $parent[$vertex] = $this->find($parent, $parent[$vertex]); // Path compression
        }
        return $parent[$vertex];
    }

    private function union($parent, $rank, $x, $y)
    {
        $rootX = $this->find($parent, $x);
        $rootY = $this->find($parent, $y);

        // Attach smaller rank tree under root of higher rank tree
        if ($rank[$rootX] < $rank[$rootY]) {
            $parent[$rootX] = $rootY;
        } elseif ($rank[$rootX] > $rank[$rootY]) {
            $parent[$rootY] = $rootX;
        } else {
            $parent[$rootY] = $rootX;
            $rank[$rootX]++;
        }
    }

    public function kruskalMST()
    {
        // Sort edges by weight
        usort($this->edges, function ($a, $b) {
            return $a->weight - $b->weight;
        });

        $parent = [];
        $rank = [];
        $mst = []; // To store the resulting MST

        // Initialize parent and rank arrays
        for ($i = 0; $i < $this->vertices; $i++) {
            $parent[$i] = $i; // Each vertex is its own parent
            $rank[$i] = 0;    // Rank is initially 0
        }

        // Iterate through edges and add to MST if no cycle is formed
        foreach ($this->edges as $edge) {
            $srcRoot = $this->find($parent, $edge->src);
            $destRoot = $this->find($parent, $edge->dest);

            if ($srcRoot != $destRoot) {
                $mst[] = $edge; // Include this edge in the MST
                $this->union($parent, $rank, $srcRoot, $destRoot);
            }
        }

        return $mst;
    }
}

// Example Usage
$graph = new Graph(4); // Graph with 4 vertices (0, 1, 2, 3)

// Add edges (src, dest, weight)
$graph->addEdge(0, 1, 10);
$graph->addEdge(0, 2, 6);
$graph->addEdge(0, 3, 5);
$graph->addEdge(1, 3, 15);
$graph->addEdge(2, 3, 4);

// Find MST using Kruskal's Algorithm
$mst = $graph->kruskalMST();

echo "Edges in the Minimum Spanning Tree (MST):\n";
foreach ($mst as $edge) {
    echo "Source: {$edge->src}, Destination: {$edge->dest}, Weight: {$edge->weight}\n";
}
